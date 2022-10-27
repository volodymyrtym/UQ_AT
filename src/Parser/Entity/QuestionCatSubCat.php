<?php

declare(strict_types=1);

namespace UqAt\Parser\Entity;

final class QuestionCatSubCat
{
    private const ANY = '*';
    private ?string $question;
    private ?string $category;
    private ?string $subCategory;

    private function __construct(?string $question, ?string $category, ?string $subCategory)
    {
        $this->question    = $question;
        $this->category    = $category;
        $this->subCategory = $subCategory;
    }

    public static function fromLine(string $line): self
    {
        $parts = explode('.', $line);

        return new self($parts[0], $parts[1] ?? null, $parts[2] ?? null);
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getSubCategory(): ?string
    {
        return $this->subCategory;
    }

    public function isAny(): bool
    {
        return !$this->question || $this->question === self::ANY;
    }
}