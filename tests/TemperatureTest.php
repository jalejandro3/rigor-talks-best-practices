<?php

declare(strict_types=1);

namespace RigorTalks\Tests;

use RigorTalks\Temperature;
use RigorTalks\Exceptions\TemperatureNegativeException;
use PHPUnit\Framework\TestCase;
use RigorTalks\TemperatureTestClass;

final class TemperatureTest extends TestCase
{
    /**
     * @test
     */
    public function tryToCreateAValidTemperatureWithNamedConstructor()
    {
        /**
         * To skip a test:
         *
         * $this->markTestSkipped();
         */
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

    /**
     * @test
     */
    public function tryToCheckIfAColdTemperatureIsSuperHot()
    {
        $this->assertFalse(
            TemperatureTestClass::take(10)->isSuperHot()
        );
    }

    /**
     * @test
     */
    public function tryToCheckIfASuperHotTemperatureIsSuperHot()
    {
        $this->assertTrue(
            TemperatureTestClass::take(100)->isSuperHot()
        );
    }
}
