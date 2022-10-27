<?php

declare(strict_types=1);

namespace UqAt\Parser\Validator;

use UqAt\Parser\Entity\Query;
use UqAt\Parser\Exception\LineParseException;

final class QueryValidator extends BaseValidator
{
    /**
     * @throws LineParseException
     */
    public static function validate(array $data, string $line): void
    {
        parent::validate($data, $line);
        if (count($data) !== 5) {
            throw LineParseException::create('Wrong line', $line, null);
        }
        [$isQuery, , , , $dateRange] = $data;
        if ($isQuery !== Query::LINE_TYPE) {
            throw LineParseException::create('Query line must begin from `D`', $line, $isQuery);
        }
        $dateRange = self::getDateRange($dateRange);
        [$from, $to] = $dateRange;
        if (!DateValidator::validateDate($from, Query::DATE_FORMAT)
            && !DateValidator::validateDate($from, 'j.m.Y')) {
            throw LineParseException::create(' date from', $line, $from);
        }
        if ($to
            && (!DateValidator::validateDate($to, Query::DATE_FORMAT)
                && !DateValidator::validateDate($from, 'j.m.Y'))) {
            throw LineParseException::create('Wrong date to', $line, $to);
        }
    }

    private static function doesDateRange(string $date): bool
    {
        return (strpos($date, '-') !== false);
    }

    private static function getDateRange(string $date): array
    {
        if (self::doesDateRange($date)) {
            return explode('-', $date);
        }
        return [$date, null];
    }
}