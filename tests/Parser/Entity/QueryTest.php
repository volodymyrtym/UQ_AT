<?php

declare(strict_types=1);

namespace Parser\Entity;

use PHPUnit\Framework\TestCase;
use UqAt\Parser\Entity\Query;

final class QueryTest extends TestCase
{
    public function testParseOk(): void
    {
        $testLine = 'D 1.1 8 P 01.01.2012-01.12.2012';
        $result   = Query::createFromLine($testLine);
        self::assertEquals('P', $result->getRespType());
        self::assertEquals(1, $result->getService());
        self::assertEquals(1, $result->getVariation());
        self::assertEquals(8, $result->getQuestion());
        self::assertNull($result->getCategory());
        self::assertNull($result->getSubCategory());
        self::assertEquals('01.01.2012', $result->getFrom()->format('d.m.Y'));
        self::assertEquals('01.12.2012', $result->getTo()->format('d.m.Y'));

        $testLine = 'D 1 * P 8.10.2012-20.11.2012';
        $result   = Query::createFromLine($testLine);
        self::assertEquals('P', $result->getRespType());
        self::assertEquals(1, $result->getService());
        self::assertNull($result->getVariation());
        self::assertTrue($result->isAnyQuestion());
        self::assertNull($result->getCategory());
        self::assertNull($result->getSubCategory());
        self::assertEquals('08.10.2012', $result->getFrom()->format('d.m.Y'));
        self::assertEquals('20.11.2012', $result->getTo()->format('d.m.Y'));

        $testLine = 'D 3 10 P 01.12.2012';
        $result   = Query::createFromLine($testLine);
        self::assertEquals('P', $result->getRespType());
        self::assertEquals(3, $result->getService());
        self::assertEquals(10, $result->getQuestion());
        self::assertFalse($result->isAnyService());
        self::assertFalse($result->isAnyQuestion());
        self::assertEquals('01.12.2012', $result->getFrom()->format('d.m.Y'));
        self::assertEquals((new \DateTimeImmutable())->format('d.m.Y'), $result->getTo()->format('d.m.Y'));
    }
}
