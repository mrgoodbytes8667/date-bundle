<?php

namespace Bytes\DateBundle\Tests\Objects\DateTimeHelper;

use Bytes\DateBundle\Helpers\DateTimeHelper;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;

class GetFromDateTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        Clock::set((new MockClock('2022-11-13 15:20:05'))->withTimeZone('UTC'));
    }

    public static function tearDownAfterClass(): void
    {
        Clock::set(new MockClock());
    }

    public function testGetYearFromDate()
    {
        self::assertEquals(2022, DateTimeHelper::getYearFromDate(DateTimeHelper::getNowUTC()));
    }

    public function testGetMinuteFromDate()
    {
        self::assertEquals(20, DateTimeHelper::getMinuteFromDate(DateTimeHelper::getNowUTC()));
    }

    public function testGetHourFromDate()
    {
        self::assertEquals(15, DateTimeHelper::getHourFromDate(DateTimeHelper::getNowUTC()));
        self::assertEquals(9, DateTimeHelper::getHourFromDate(DateTimeHelper::getNowChicago()));
    }

    public function testGetMonthFromDate()
    {
        self::assertEquals(11, DateTimeHelper::getMonthFromDate(DateTimeHelper::getNowUTC()));
    }

    public function testGetSecondFromDate()
    {
        self::assertEquals(5, DateTimeHelper::getSecondFromDate(DateTimeHelper::getNowUTC()));
    }

    public function testGetDayFromDate()
    {
        self::assertEquals(13, DateTimeHelper::getDayFromDate(DateTimeHelper::getNowUTC()));
    }

    public function testGetDayOfWeekFromDate()
    {
        self::assertEquals(0, DateTimeHelper::getDayOfWeekFromDate(DateTimeHelper::getNowUTC()));
    }

    public function testGetDayOfWeekISO8601FromDate()
    {
        self::assertEquals(7, DateTimeHelper::getDayOfWeekISO8601FromDate(DateTimeHelper::getNowUTC()));
    }

    public function testToDoctrine()
    {
        self::assertEquals(Clock::get()->now(), DateTimeHelper::toDoctrine(DateTimeHelper::getNowUTC()));
    }

    public function testToDoctrineNoSeconds()
    {
        self::assertEquals((new MockClock('2022-11-13 15:20:00'))->now(), DateTimeHelper::toDoctrineNoSeconds(DateTimeHelper::getNowUTC()));
        self::assertEquals((new MockClock('2022-11-13 15:20:00'))->now(), DateTimeHelper::toDoctrineNoSeconds(DateTime::createFromInterface((new MockClock('2022-11-13 15:20:00'))->now())));
    }
}
