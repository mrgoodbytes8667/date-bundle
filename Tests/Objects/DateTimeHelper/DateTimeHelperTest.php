<?php

namespace Bytes\DateBundle\Tests\Objects\DateTimeHelper;

use Bytes\DateBundle\Helpers\DateTimeHelper;
use DateInvalidTimeZoneException;
use DateTimeImmutable;
use Exception;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;

class DateTimeHelperTest extends TestCase
{
    public static function provideIncreaseMinutesNoTensToValueException(): Generator
    {
        yield '10' => [10];
        yield '100' => [100];
        yield '1,2,3,10' => [[1, 2, 3, 10]];
    }

    public static function provideWeekdays(): Generator
    {
        yield '3/30 - 4/30' => ['start' => '2020-03-30', 'end' => '2020-04-30', 'count' => 23];
        yield '3/28 - 3/28' => ['start' => '2020-03-28', 'end' => '2020-03-28', 'count' => 0];
        yield '3/28 - 3/29' => ['start' => '2020-03-28', 'end' => '2020-03-29', 'count' => 0];
        yield '3/28 - 3/30' => ['start' => '2020-03-28', 'end' => '2020-03-30', 'count' => 0];
        yield '3/28 - 3/31' => ['start' => '2020-03-28', 'end' => '2020-03-31', 'count' => 1];
        yield '3/29 - 3/28' => ['start' => '2020-03-29', 'end' => '2020-03-28', 'count' => 0];
    }

    public static function provideDateReduce(): Generator
    {
        yield '16:36' => ['now' => new DateTimeImmutable('2020-03-30 16:36'), 'reduce' => new DateTimeImmutable('2020-03-30 16:35'), 'increase' => new DateTimeImmutable('2020-03-30 16:45')];
        yield '16:01' => ['now' => new DateTimeImmutable('2020-03-30 16:01'), 'reduce' => new DateTimeImmutable('2020-03-30 15:55'), 'increase' => new DateTimeImmutable('2020-03-30 16:05')];
        yield '0:01' => ['now' => new DateTimeImmutable('2020-03-30 0:01'), 'reduce' => new DateTimeImmutable('2020-03-29 23:55'), 'increase' => new DateTimeImmutable('2020-03-30 0:05')];
        yield '23:59' => ['now' => new DateTimeImmutable('2020-03-29 23:59'), 'reduce' => new DateTimeImmutable('2020-03-29 23:55'), 'increase' => new DateTimeImmutable('2020-03-30 0:05')];
    }

    public function testGetTimezoneFromDate()
    {
        Clock::set((new MockClock('2022-11-16 15:20:05'))->withTimeZone('UTC'));

        self::assertEquals('Z', DateTimeHelper::getTimezoneFromDate(DateTimeHelper::getNowUTC()));

        Clock::set((new MockClock('2022-11-16 15:20:05'))->withTimeZone('UTC'));

        self::assertEquals('-06:00', DateTimeHelper::getTimezoneFromDate(DateTimeHelper::getNowChicago()));

        Clock::set((new MockClock('2022-11-16 15:20:05'))->withTimeZone('America/Chicago'));

        self::assertEquals('-06:00', DateTimeHelper::getTimezoneFromDate(DateTimeHelper::getNowChicago()));
    }

    public function testFormatFull()
    {
        Clock::set((new MockClock('2022-11-16 15:20:00'))->withTimeZone('UTC'));

        self::assertEquals('Wednesday, November 16, 2022 @ 3:20 pm UTC', Clock::get()->now()->format(DateTimeHelper::FORMAT_FULL));
    }

    /**
     * @dataProvider provideDateReduce
     *
     * @return void
     *
     * @throws Exception
     */
    public function testReduceMinutesNoTensToValue($now, $reduce)
    {
        self::assertEquals($reduce, DateTimeHelper::reduceMinutesNoTensToValue($now, [5]));
        self::assertEquals($reduce, DateTimeHelper::reduceMinutesNoTensToValue($reduce, [5]));
    }

    /**
     * @dataProvider provideDateReduce
     *
     * @return void
     *
     * @throws Exception
     */
    public function testIncreaseMinutesNoTensToValue($now, $reduce, $increase)
    {
        self::assertEquals($increase, DateTimeHelper::increaseMinutesNoTensToValue($now, [5]));
        self::assertEquals($increase, DateTimeHelper::increaseMinutesNoTensToValue($increase, [5]));
    }

    /**
     * @dataProvider provideIncreaseMinutesNoTensToValueException
     *
     * @return void
     *
     * @throws DateInvalidTimeZoneException
     */
    public function testIncreaseMinutesNoTensToValueException($i)
    {
        Clock::set((new MockClock('2022-11-16 15:21:00'))->withTimeZone('UTC'));

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Minutes should be less than 10');
        DateTimeHelper::increaseMinutesNoTensToValue(DateTimeHelper::getNowUTC(), $i);
    }

    /**
     * @dataProvider provideWeekdays
     */
    public function testCountWeekdaysInRange($start, $end, $count): void
    {
        self::assertSame($count, DateTimeHelper::countWeekdaysInRange(new DateTimeImmutable($start), new DateTimeImmutable($end)));
    }

    public function testGetClock()
    {
        self::assertEquals(Clock::get()->withTimeZone('UTC')->now(), DateTimeHelper::getClock('UTC')->now());
    }
}
