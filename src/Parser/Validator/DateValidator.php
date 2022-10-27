<?php

declare(strict_types=1);

namespace UqAt\Parser\Validator;

final class DateValidator extends BaseValidator
{
    public static function validateDate(string $date, string $format):bool
    {
        $d = \DateTimeImmutable::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }
}