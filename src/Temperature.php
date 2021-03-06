<?php

declare(strict_types=1);

namespace RigorTalks;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use RigorTalks\Contracts\ColdTresholdInterface;
use RigorTalks\Exceptions\TemperatureNegativeException;

class Temperature
{
    /**
     * @var int
     */
    private int $measure;

    //Use object itself to return it

    /**
     * @param int $measure
     * @return self
     * @throws TemperatureNegativeException
     */
    public static function take(int $measure): self
    {
        return new static($measure);
    }

    /**
     * @param int $measure
     * @throws TemperatureNegativeException
     */
    private function __construct(int $measure)
    {
        $this->setMeasure($measure);
    }

    /*Metodologia Clean Code recomienda poner metodos privados justo despues de su implementacion*/

    /**
     * @param int $measure
     * @throws TemperatureNegativeException
     */
    private function setMeasure(int $measure)
    {
        //Guard Clause
        $this->checkMeasureIsPositive($measure);

        $this->measure = $measure;
    }

    /**
     * @param int $measure
     * @throws TemperatureNegativeException
     */
    private function checkMeasureIsPositive(int $measure)
    {
        if ($measure < 0) {
            throw TemperatureNegativeException::fromMeasure($measure);
        }
    }

    /**
     * @return int
     */
    public function measure(): int
    {
        return $this->measure;
    }

    /**
     * Coupled code
     *
     * How to test it:
     *
     * 1. send the database code to a private method: getTreshold
     * 2. change private getTreshold to protected getTreshold
     * 3. create a <Name>TestClass to extend getTreshold behavior to avoid database connection
     * 4. in our test (TemperatureTest) methods with can change Temperature (father class) by
     * TemperatureTestClass (child)
     *
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function isSuperHot(): bool
    {
        $treshold = (int)$this->getTreshold();

        return $this->measure() > $treshold;
    }

    /**
     * @return false|mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getTreshold()
    {
        $conn = DriverManager::getConnection([
            'dbname' => 'rigor_talks',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost',
            'driver' => 'pdo_mysql'
        ], new Configuration());

        return $conn->fetchOne('SELECT value FROM configuration WHERE name="hot_treshold"');
    }

    /**
     * @param ColdTresholdInterface $coldTreshold
     * @return bool
     */
    public function isSuperCold(ColdTresholdInterface $coldTreshold): bool
    {
        $treshold = $coldTreshold->getTreshold();

        return $this->measure() < $treshold;
    }

    /**
     * @param $station
     * @return static
     * @throws TemperatureNegativeException
     */
    public static function fromStation($station): self
    {
        return new static(
            $station->sensor()->temperature()->measure()
        );
    }

    /**
     * Method to prove Immutability.
     *
     * We need to sum two temperature, but, we must ensure that the temperature result is not
     * affected by another process in the middle, so, we return a new instance of temperature
     * with the value of the sum operation.
     *
     * @param Temperature $anotherTemperature
     * @return $this
     * @throws TemperatureNegativeException
     */
    public function sum(self $anotherTemperature): self
    {
        return new static($this->measure() + $anotherTemperature->measure());
    }
}
