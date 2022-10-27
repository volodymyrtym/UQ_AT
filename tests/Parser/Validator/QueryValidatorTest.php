<?php

declare(strict_types=1);

namespace Parser\Validator;

use PHPUnit\Framework\TestCase;
use UqAt\Parser\Exception\LineParseException;
use UqAt\Parser\Validator\QueryValidator;

final class QueryValidatorTest extends TestCase
{
    public function testWrongTime(): void
    {
        $testLine = 'D 3 10 P 41.12.2012';
        $byPart   = \explode(' ', $testLine, 5);
        $this->expectException(LineParseException::class);
        QueryValidator::validate($byPart, $testLine);
    }

    public function testWrongLine(): void
    {
        $testLine = 'D A3 10 P 01.12.2012 01.12.2012';
        $byPart   = \explode(' ', $testLine);
        $this->expectException(LineParseException::class);
        QueryValidator::validate($byPart, $testLine);
    }
}
