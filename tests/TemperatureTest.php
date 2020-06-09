<?php

declare(strict_types=1);

namespace RigorTalks\Tests;

use RigorTalks\Temperature;
use RigorTalks\Exceptions\TemperatureNegativeException;
use PHPUnit\Framework\TestCase;

final class TemperatureTest extends TestCase
{
    /**
     * @test
     */
    public function tryToCreateAValidTemperatureWithNamedConstructor()
    {
        $measure = 10;
        $this->assertSame($measure, (Temperature::take(10))->measure());
    }

    /**
     * @test
     */
    public function tryToCreateANonValidTemperature()
    {
        $this->expectException(TemperatureNegativeException::class);
        Temperature::take(-1);
    }

    /**
     * @test
     */
    public function tryToCreateAValidTemperature()
    {
        $measure = 10;
        $this->assertSame($measure, (Temperature::take(10))->measure());
    }
}
