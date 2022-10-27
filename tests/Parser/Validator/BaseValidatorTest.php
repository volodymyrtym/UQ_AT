<?php

declare(strict_types=1);

namespace Parser\Validator;

use PHPUnit\Framework\TestCase;
use UqAt\Parser\Exception\LineParseException;
use UqAt\Parser\Validator\BaseValidator;

final class BaseValidatorTest extends TestCase
{
    public function testWrongService(): void
    {
        $testLine = 'D A3 10 P 01.12.2012';
        $byPart   = \explode(' ', $testLine, 5);
        $this->expectException(LineParseException::class);
        BaseValidator::validate($byPart, $testLine);
    }
}
