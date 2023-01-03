<?php

declare(strict_types=1);

namespace Flow\Test\Flow;

use Flow\Driver\AmpDriver;
use Flow\Driver\ReactDriver;
use Flow\Driver\SwooleDriver;
use Flow\IpStrategy\LinearIpStrategy;
use Flow\IpStrategy\MaxIpStrategy;
use Flow\IpStrategy\StackIpStrategy;
use PHPUnit\Framework\TestCase;

abstract class AbstractFlowTest extends TestCase
{
    /**
     * @param array<array<mixed>> $datas
     * @param array<mixed>|null   $mix
     *
     * @return array<array<mixed>>
     */
    protected function matrix(array $datas, ?array $mix = null): array
    {
        if (null === $mix) {
            $drivers = [
                'amp' => static function (): AmpDriver { return new AmpDriver(); },
                'react' => static function (): ReactDriver { return new ReactDriver(); },
                'swoole' => static function (): SwooleDriver { return new SwooleDriver(); },
            ];

            $strategies = [
                'linear' => static function (): LinearIpStrategy { return new LinearIpStrategy(); },
                'max' => static function (): MaxIpStrategy { return new MaxIpStrategy(); },
                'stack' => static function (): StackIpStrategy { return new StackIpStrategy(); },
            ];

            return $this->matrix($this->matrix($datas, $strategies), $drivers);
        }

        $mixDatas = [];
        foreach ($datas as $dataKey => $dataValues) {
            foreach ($mix as $mixKey => $mixValue) {
                $mixDatas["$mixKey.$dataKey"] = [$mixValue(), ...$dataValues];
            }
        }

        return $mixDatas;
    }
}
