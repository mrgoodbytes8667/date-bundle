<?php

namespace Bytes\DateBundle\Tests\Objects;

use Bytes\DateBundle\Helpers\DateTimeHelper;
use DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeHelperTest extends TestCase
{
    public function testGetYearFromDate()
    {
        $date = new DateTime('2024-01-01');
        $this->assertEquals(2024, DateTimeHelper::getYearFromDate($date));
    }
}
