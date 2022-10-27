<?php

declare(strict_types=1);

namespace UqAt\Parser\Validator;

use UqAt\Parser\Entity\WaitingTime;
use UqAt\Parser\Exception\LineParseException;

final class WaitingTimeValidator extends BaseValidator
{
    /**
     * @throws LineParseException
     */
    public static function validate(array $data, string $line): void
    {
        if (count($data) !== 6) {
            throw LineParseException::create('Wrong line', $line, null);
        }
        [$isWaitTime, , , , $date, $waitTime] = $data;
        if ($isWaitTime !== WaitingTime::LINE_TYPE) {
            throw LineParseException::create('Waiting time line must begin from `C`', $line, $isWaitTime);
        }
        if (!DateValidator::validateDate($date, WaitingTime::DATE_FORMAT)) {
            throw LineParseException::create('Wrong date', $line, $date);
        }
        if (!is_numeric($waitTime)) {
            throw LineParseException::create('Waiting time must be int', $line, $waitTime);
        }
        parent::validate($data, $line);
    }
}