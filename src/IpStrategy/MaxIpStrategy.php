<?php

declare(strict_types=1);

namespace Flow\IpStrategy;

use Flow\Event\PopEvent;
use Flow\Event\PullEvent;
use Flow\Event\PushEvent;
use Flow\IpStrategyEvent;
use Flow\IpStrategyInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @template T
 */
class MaxIpStrategy implements IpStrategyInterface
{
    private IpStrategyInterface $ipStrategy;

    private EventDispatcherInterface $dispatcher;

    private int $processing = 0;

    public function __construct(
        private int $max = 1,
        ?IpStrategyInterface $ipStrategy = null,
        EventDispatcherInterface $dispatcher = null,
    ) {
        $this->ipStrategy = $ipStrategy ?? new LinearIpStrategy();
        $this->dispatcher = $dispatcher ?? new EventDispatcher();
        $this->dispatcher->addSubscriber($this->ipStrategy);
    }

    public static function getSubscribedEvents()
    {
        return [
            IpStrategyEvent::PUSH => 'push',
            IpStrategyEvent::PULL => 'pull',
            IpStrategyEvent::POP => 'pop',
        ];
    }

    public function push(PushEvent $event): void
    {
        $this->dispatcher->dispatch($event, IpStrategyEvent::PUSH);
    }

    /**
     * @return PullEvent<T>
     */
    public function pull(PullEvent $event): void
    {
        if ($this->processing < $this->max) {
            $ip = $this->dispatcher->dispatch($event, IpStrategyEvent::PULL)->getIp();
            if ($ip) {
                $this->processing++;
            }

            $event->setIp($ip);
        }
    }

    /**
     * @param PopEvent<T> $event
     */
    public function pop(PopEvent $event): void
    {
        $this->dispatcher->dispatch($event, IpStrategyEvent::POP);
        $this->processing--;
    }
}
