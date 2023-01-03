<?php

declare(strict_types=1);

namespace Flow\Test\Driver;

use Exception;
use Flow\DriverInterface;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class DriverTest extends TestCase
{
    abstract protected function createDriver(): DriverInterface;

    public function testCoroutine(): void
    {
        $driver = $this->createDriver();
        $driver->coroutine(static function () {
        }, function (?Throwable $e) use ($driver) {
            $driver->stop();
            $this->assertNull($e);
        })();
        $driver->start();
    }

    public function testCoroutineError(): void
    {
        $driver = $this->createDriver();
        $driver->coroutine(static function () {
            throw new Exception();
        }, function (?Throwable $e) use ($driver) {
            $driver->stop();
            $this->assertNotNull($e);
        })();
        $driver->start();
    }

    public function testTick(): void
    {
        $i = 0;
        $driver = $this->createDriver();
        $driver->tick(1, function () use (&$i) {
            $i++;
        });
        $driver->coroutine(function () use ($driver, &$i) {
            usleep(3000);
            $driver->stop();

            $this->assertGreaterThan(3, $i);
        })();
        $driver->start();
    }
}
