<?php

declare(strict_types=1);

declare(ticks=1000);

namespace Flow\Driver;

use Closure;
use Fiber;
use Flow\DriverInterface;
use Flow\Event\PopEvent;
use Flow\Event\PullEvent;
use Flow\Event\PushEvent;
use Flow\Exception\RuntimeException;
use Flow\Ip;
use Flow\IpStrategyEvent;
use Throwable;

use function array_key_exists;
use function count;

/**
 * @template TArgs
 * @template TReturn
 *
 * @implements DriverInterface<TArgs,TReturn>
 */
class FiberDriver implements DriverInterface
{
    /**
     * @var array<mixed>
     */
    private array $ticks = [];

    public function async(Closure $callback): Closure
    {
        return static function () {};
    }

    public function await(array &$stream): void
    {
        $async = static function ($ip, $fnFlows, $index, $isTick) {
            $fiber = new Fiber($fnFlows[$index]['job']);

            $exception = null;

            try {
                if ($ip->data === null) {
                    $fiber->start();
                } else {
                    $fiber->start($ip->data);
                }
            } catch (Throwable $fiberException) {
                $exception = $fiberException;
            }

            return [
                'index' => $index,
                'fiber' => $fiber,
                'exception' => $exception,
                'ip' => $ip,
                'isTick' => $isTick,
            ];
        };

        $tick = 0;
        $fiberDatas = [];
        while ($stream['ips'] > 0 or count($this->ticks) > 0) {
            foreach ($this->ticks as [
                'interval' => $interval,
                'callback' => $callback,
            ]) {
                if ($tick % $interval === 0) {
                    $ip = new Ip();
                    $stream['ips']++;
                    $fiberDatas[] = $async($ip, [['job' => $callback]], 0, true);
                }
            }

            $nextIp = null;
            do {
                foreach ($stream['dispatchers'] as $index => $dispatcher) {
                    $nextIp = $dispatcher->dispatch(new PullEvent(), IpStrategyEvent::PULL)->getIp();
                    if ($nextIp !== null) {
                        $fiberDatas[] = $async($nextIp, $stream['fnFlows'], $index, false);
                    }
                }
            } while ($nextIp !== null);

            foreach ($fiberDatas as $i => $fiberData) {
                if (!$fiberData['fiber']->isTerminated() and $fiberData['fiber']->isSuspended()) {
                    try {
                        $fiberData['fiber']->resume();
                    } catch (Throwable $exception) {
                        $fiberDatas[$i]['exception'] = $exception;
                    }
                } else {
                    if ($fiberData['exception'] === null) {
                        $data = $fiberData['fiber']->getReturn();

                        if ($fiberData['isTick'] === false and array_key_exists($fiberData['index'] + 1, $stream['fnFlows'])) {
                            $ip = new Ip($data);
                            $stream['ips']++;
                            $stream['dispatchers'][$fiberData['index'] + 1]->dispatch(new PushEvent($ip), IpStrategyEvent::PUSH);
                            $fiberDatas[] = $async($ip, $stream['fnFlows'], $fiberData['index'] + 1, false);
                        }
                    } elseif (array_key_exists($fiberData['index'], $stream['fnFlows']) and $stream['fnFlows'][$fiberData['index']]['errorJob'] !== null) {
                        $stream['fnFlows'][$fiberData['index']]['errorJob'](
                            new RuntimeException($fiberData['exception']->getMessage(), $fiberData['exception']->getCode(), $fiberData['exception'])
                        );
                    }
                    $stream['dispatchers'][$fiberData['index']]->dispatch(new PopEvent($fiberData['ip']), IpStrategyEvent::POP);
                    $stream['ips']--;
                    unset($fiberDatas[$i]);
                }
            }

            $tick++;
        }
    }

    public function delay(float $seconds): void
    {
        $date = time();
        do {
            Fiber::suspend();
        } while (time() - $date < $seconds);
    }

    public function tick($interval, Closure $callback): Closure
    {
        $i = count($this->ticks) - 1;
        $this->ticks[$i] = [
            'interval' => $interval,
            'callback' => $callback,
        ];

        return function () use ($i) {
            unset($this->ticks[$i]);
        };
    }
}