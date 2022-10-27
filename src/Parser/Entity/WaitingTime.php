<?php

declare(strict_types=1);

namespace UqAt\Parser\Entity;

use UqAt\Parser\Exception\LineParseException;
use UqAt\Parser\Validator\WaitingTimeValidator;

final class WaitingTime extends AbstractLine
{
    private \DateTimeImmutable $date;
    private int                $waitTime;
    public const LINE_TYPE = 'C';

    public function __construct(
        ServiceVariation   $serviceAndVariation,
        QuestionCatSubCat  $questionCatSubCat,
        string             $respType,
        \DateTimeImmutable $date,
        int                $waitTime
    ) {
        parent::__construct($serviceAndVariation, $questionCatSubCat, $respType);
        $this->date     = $date;
        $this->waitTime = $waitTime;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getWaitTime(): int
    {
        return $this->waitTime;
    }

    /**
     * @throws LineParseException
     */
    public static function createFromLine(string $line): self
    {
        $byPart = \explode(' ', $line, 6);
        WaitingTimeValidator::validate($byPart, $line);
        [, $serviceAndVariation, $questionCatSubCat, $respType, $date, $waitTime] = $byPart;

        return new self(
            ServiceVariation::fromLine($serviceAndVariation),
            QuestionCatSubCat::fromLine($questionCatSubCat),
            $respType,
            \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $date),
            (int)$waitTime
        );
    }
}