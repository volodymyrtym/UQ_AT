<?php

declare(strict_types=1);

namespace UqAt\Parser\Entity;

final class ServiceVariation
{
    public const ANY = '*';
    private ?string $service;
    private ?string $variation;

    private function __construct(?string $service, ?string $variation)
    {
        $this->service   = $service;
        $this->variation = $variation;
    }

    public static function fromLine(string $line): self
    {
        $serviceVariation = \explode('.', $line);

        return new ServiceVariation($serviceVariation[0], $serviceVariation[1] ?? null);
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function getVariation(): ?string
    {
        return $this->variation;
    }

    public function isAny(): bool
    {
        return $this->service === null || $this->service === self::ANY;
    }
}