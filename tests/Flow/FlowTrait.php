<?php

declare(strict_types=1);

namespace Flow\Test\Flow;

use Closure;
use Flow\Driver\AmpDriver;
use Flow\Driver\FiberDriver;
use Flow\Driver\ReactDriver;
use Flow\Driver\SwooleDriver;
use Flow\IpStrategy\LinearIpStrategy;
use Flow\IpStrategy\MaxIpStrategy;
use Flow\IpStrategy\StackIpStrategy;

trait FlowTrait
{
    /**
     * @return array<array<mixed>>
     */
    protected static function matrix(Closure $datas): array
    {
        $drivers = [
            // 'amp' => fn (): AmpDriver => new AmpDriver(),
            // 'fiber' => fn (): FiberDriver => new FiberDriver(),
            'react' => fn (): ReactDriver => new ReactDriver(),
            // 'swoole' => fn (): SwooleDriver => new SwooleDriver(),
        ];

        $strategies = [
            'linear' => fn (): LinearIpStrategy => new LinearIpStrategy(),
            'max' => fn (): MaxIpStrategy => new MaxIpStrategy(),
            'stack' => fn (): StackIpStrategy => new StackIpStrategy(),
        ];

        $matrixDatas = [];
        foreach ($drivers as $keyDriver => $driverBuilder) {
            $driver = $driverBuilder();
            $dataValues = $datas($driver);
            foreach ($strategies as $keyStrategy => $strategyBuilder) {
                $strategy = $strategyBuilder();
                foreach ($dataValues as $key => $values) {
                    $matrixDatas["{$keyDriver}.{$keyStrategy}.{$key}"] = [$driver, $strategy, ...$values];
                }
            }
        }

        return $matrixDatas;
    }
}