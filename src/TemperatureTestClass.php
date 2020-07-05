<?php

declare(strict_types=1);

namespace RigorTalks;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use RigorTalks\Exceptions\TemperatureNegativeException;

class TemperatureTestClass extends Temperature
{
    /**
     * Implementation class to test coupled code
     */
    protected function getTreshold()
    {
        return 50;
    }
}
