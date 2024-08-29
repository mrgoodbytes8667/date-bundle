<?php

namespace Bytes\DateBundle\Enums;

use Bytes\DateBundle\Helpers\DateTimeHelper;
use Bytes\EnumSerializerBundle\Enums\BackedEnumInterface;
use Bytes\EnumSerializerBundle\Enums\BackedEnumTrait;
use DateTimeInterface;

enum Month: int implements BackedEnumInterface
{
    use BackedEnumTrait;

    case JANUARY = 1;
    case FEBRUARY = 2;
    case MARCH = 3;
    case APRIL = 4;
    case MAY = 5;
    case JUNE = 6;
    case JULY = 7;
    case AUGUST = 8;
    case SEPTEMBER = 9;
    case OCTOBER = 10;
    case NOVEMBER = 11;
    case DECEMBER = 12;

    public static function fromDate(DateTimeInterface $date): Month
    {
        return Month::from(DateTimeHelper::getMonthFromDate($date));
    }
}
