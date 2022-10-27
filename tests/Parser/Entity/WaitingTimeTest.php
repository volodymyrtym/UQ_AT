<?php

declare(strict_types=1);

namespace Parser\Entity;

use PHPUnit\Framework\TestCase;
use UqAt\Parser\Entity\WaitingTime;

final class WaitingTimeTest extends TestCase
{
    public function testParseOk(): void
    {
        $testLine = 'C 1.2 8.15.1 P 15.10.2012 83';
        $result   = WaitingTime::createFromLine($testLine);
        self::assertEquals('P', $result->getRespType());
        self::assertEquals(83, $result->getWaitTime());
        self::assertEquals('15.10.2012', $result->getDate()->format('d.m.Y'));
        self::assertEquals(1, $result->getService());
        self::assertEquals(2, $result->getVariation());
        self::assertEquals(8, $result->getQuestion());
        self::assertEquals(15, $result->getCategory());
        self::assertEquals(1, $result->getSubCategory());

        $testLine = 'C * 8 P 15.10.2012 83';
        $result   = WaitingTime::createFromLine($testLine);
        self::assertTrue($result->isAnyService());
        self::assertEquals(8, $result->getQuestion());
        $testLine = 'C * * P 15.10.2012 83';
        $result   = WaitingTime::createFromLine($testLine);
        self::assertTrue($result->isAnyService());

        $testLine = 'C * 1.3 P 15.10.2012 83';
        $result   = WaitingTime::createFromLine($testLine);
        self::assertTrue($result->isAnyService());
        self::assertEquals(1, $result->getQuestion());
        self::assertEquals(3, $result->getCategory());

        $testLine = 'C 15 1.3 P 15.10.2012 83';
        $result   = WaitingTime::createFromLine($testLine);
        self::assertEquals(15, $result->getService());
        self::assertEquals(1, $result->getQuestion());
        self::assertEquals(3, $result->getCategory());
    }

}