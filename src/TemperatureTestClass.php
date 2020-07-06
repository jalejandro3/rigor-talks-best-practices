<?php

declare(strict_types=1);

namespace RigorTalks;

final class TemperatureTestClass extends Temperature
{
    /**
     * Implementation class to test coupled code
     */
    protected function getTreshold()
    {
        return 10;
    }
}
