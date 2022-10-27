<?php

declare(strict_types=1);

namespace UqAt\Parser\Entity;

use UqAt\Parser\Exception\LineParseException;
use UqAt\Parser\Validator\QueryValidator;

final class Query extends AbstractLine
{
    public const LINE_TYPE        = 'D';

    private \DateTimeInterface  $from;
    private \DateTimeInterface $to;

    private function __construct(
        ServiceVariation   $serviceVariation,
        QuestionCatSubCat  $questionCatSubCat,
        string             $respType,
        \DateTimeInterface $from,
        \DateTimeInterface $to
    ) {
        parent::__construct($serviceVariation, $questionCatSubCat, $respType);
        $this->from = $from;
        $this->to   = $to;
    }

    public function getFrom(): \DateTimeInterface
    {
        return $this->from;
    }

    public function getTo(): \DateTimeInterface
    {
        return $this->to;
    }

    /**
     * @throws LineParseException
     */
    public static function createFromLine(string $line): self
    {
        $byPart = \explode(' ', $line, 5);
        QueryValidator::validate($byPart, $line);
        [, $serviceAndVariation, $questionCatSubCat, $respType, $dateRange] = $byPart;
        $from = $dateRange;
        $to   = (new \DateTimeImmutable())->format(self::DATE_FORMAT);
        if (strpos($dateRange, '-') !== false) {
            [$from, $to] = explode('-', $dateRange);
        }

        return new self(
            ServiceVariation::fromLine($serviceAndVariation),
            QuestionCatSubCat::fromLine($questionCatSubCat),
            $respType,
            \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $from),
            \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $to)
        );
    }
}
