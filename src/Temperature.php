<?php

declare(strict_types=1);

namespace RigorTalks;

use RigorTalks\Exceptions\TemperatureNegativeException;

final class Temperature
{
    private $measure;

    public static function take(int $measure): self
    {
        return new static($measure);
    }

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
        //Clausula de guardia (Guard Clauses)
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

    public function measure(): int
    {
        return $this->measure;
    }
}
