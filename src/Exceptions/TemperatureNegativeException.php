<?php

declare(strict_types=1);

namespace RigorTalks\Exceptions;

use \Exception;

final class TemperatureNegativeException extends Exception {
    public static function fromMeasure(int $measure)
    {
        return new static("Measure {$measure} must be positive.");
    }
}
