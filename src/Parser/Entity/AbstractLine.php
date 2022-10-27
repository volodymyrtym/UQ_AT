<?php

declare(strict_types=1);

namespace UqAt\Parser\Entity;

use UqAt\Parser\Exception\LineParseException;

abstract class AbstractLine
{
    public const DATE_FORMAT = 'd.m.Y';

    private ServiceVariation  $serviceVariation;
    private QuestionCatSubCat $questionCatSubCat;
    private string            $respType;

    public function __construct(
        ServiceVariation  $serviceVariation,
        QuestionCatSubCat $questionCatSubCat,
        string            $respType
    ) {
        $this->serviceVariation  = $serviceVariation;
        $this->questionCatSubCat = $questionCatSubCat;
        $this->respType          = $respType;
    }

    public function getService(): ?string
    {
        return $this->serviceVariation->getService();
    }

    public function getVariation(): ?string
    {
        return $this->serviceVariation->getVariation();
    }

    public function isAnyService(): bool
    {
        return $this->serviceVariation->isAny();
    }

    public function getRespType(): string
    {
        return $this->respType;
    }

    public function getQuestion(): ?string
    {
        return $this->questionCatSubCat->getQuestion();
    }

    public function getCategory(): ?string
    {
        return $this->questionCatSubCat->getCategory();
    }

    public function getSubCategory(): ?string
    {
        return $this->questionCatSubCat->getSubCategory();
    }

    public function isAnyQuestion(): bool
    {
        return $this->questionCatSubCat->isAny();
    }

    abstract public static function createFromLine(string $line): self;

    /**
     * @param string $line
     *
     * @return WaitingTime|Query
     * @throws LineParseException
     */
    public static function fabric(string $line): self
    {
        $type = $line[0];
        switch ($type) {
            case WaitingTime::LINE_TYPE:
                return WaitingTime::createFromLine($line);
            case Query::LINE_TYPE:
                return Query::createFromLine($line);
            default:
                throw LineParseException::create($line, 'Unknown line type', null);
        }
    }

    public function isWaitingTime(): bool
    {
        return $this instanceof WaitingTime;
    }
}
