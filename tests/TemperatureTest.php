<?php

declare(strict_types=1);

namespace RigorTalks\Tests;

use RigorTalks\Contracts\ColdTresholdInterface;
use RigorTalks\Temperature;
use RigorTalks\Exceptions\TemperatureNegativeException;
use PHPUnit\Framework\TestCase;
use RigorTalks\TemperatureTestClass;

/**
 * Class TemperatureTest
 *
 * This class implements Self-Shunt Pattern
 * @link (https://wiki.c2.com/?SelfShuntPattern)
 *
 * @package RigorTalks\Tests
 */
final class TemperatureTest extends TestCase implements ColdTresholdInterface
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

    /**
     * @test
     */
    public function tryToCheckIfASuperColdTemperatureIsSuperCold()
    {
        $this->assertTrue(
            Temperature::take(10)->isSuperCold(
                $this
            )
        );
    }

    /**
     * @test
     */
    public function tryToCheckIfASuperColdTemperatureIsSuperColdWithAnomClass()
    {
        $this->assertTrue(
            Temperature::take(10)->isSuperCold(
                new class implements ColdTresholdInterface {
                    public function getTreshold(): int
                    {
                        return 30;
                    }
                }
            )
        );
    }

    /**
     * Method implementation from ColdTresholdInterface
     *
     * @return int
     */
    public function getTreshold(): int
    {
        return 50;
    }

    /**
     * @test
     */
    public function tryToCreateTemperatureFromStation()
    {
        $this->assertSame(
            50,
            Temperature::fromStation(
                $this
            )->measure()
        );
    }

    public function sensor()
    {
        return $this;
    }

    public function temperature()
    {
        return $this;
    }

    public function measure()
    {
        return 50;
    }

    /**
     * @test
     */
    public function tryToSumTwoMeasures()
    {
        $a = Temperature::take(50);
        $b = Temperature::take(50);

        $c = $a->sum($b);

        $this->assertSame(100, $c->measure());
        $this->assertNotSame($c, $a);
        $this->assertNotSame($c, $b);
    }
}
