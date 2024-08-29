<?php

namespace Bytes\DateBundle\Tests\Enums;

use Bytes\Common\Faker\Factory;
use Bytes\DateBundle\Enums\Month;
use Bytes\DateBundle\Helpers\DateTimeHelper;
use Bytes\EnumSerializerBundle\PhpUnit\EnumAssertions;
use Generator;
use PHPUnit\Framework\TestCase;

class MonthTest extends TestCase
{
    /**
     * @dataProvider provideAll
     */
    public function testAll(Month $enum, int $value): void
    {
        EnumAssertions::assertIsEnum($enum);
        EnumAssertions::assertEqualsEnum($enum, $value);
        EnumAssertions::assertSameEnumValue($enum, $value);
    }

    public function provideAll(): Generator
    {
        foreach (Month::cases() as $enum) {
            yield $enum->value => ['enum' => $enum, 'value' => $enum->value];
        }
    }

    /**
     * @dataProvider provideAll
     */
    public function testTryFromSuccessful(Month $enum, int $value): void
    {
        EnumAssertions::assertSameEnum($enum, Month::tryFrom($value));
    }

    public function testTryFromUnsuccessful(): void
    {
        self::assertNull(Month::tryFrom(-1));
        self::assertNull(Month::tryFrom(13));
        self::assertNull(Month::tryFrom(15));
    }

    public function testFromMonth()
    {
        $faker = Factory::create();
        $date = $faker->dateTime();
        $month = DateTimeHelper::getMonthFromDate($date);

        self::assertSame($month, Month::from($month)->value);

        self::assertSame($month, Month::fromDate($date)->value);
    }
}
