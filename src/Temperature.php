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
        //Clausula de guarda (Guard Clauses)
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
     * Copled code
     *
     * How to text it:
     *
     * 1. send the database code to a private method: getTreshold
     * 2. change private getTreshold to protected getTreshold
     * 3. create a <Name>TestClass to extend getTreshold behavior to avoid database connection
     * 4. in our test (TemperatureTest) methods with can change Temperature (father class) by TemperatureTestClass (son)
     *
     * @return bool
     */
    public function isSuperHot(): bool
    {
        $treshold = (int)$this->getTreshold();

        return $this->measure() > $treshold;
    }

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

    public function isSuperCold(ColdTresholdInterface $coldTreshold): bool
    {
        $treshold = $coldTreshold->getTreshold();

        return $this->measure() < $treshold;
    }

    /**
     * Self-shunt broken law of demeter principle
     *
     * We use a trick to test method that does not comply with the law of demeter.
     * In our test we will create methods that will be working as the object called in fromStation.
     *
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
}
