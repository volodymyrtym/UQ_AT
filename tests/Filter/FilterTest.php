<?php

declare(strict_types=1);

namespace Filter;

use PHPUnit\Framework\TestCase;
use UqAt\Filter\Filter;
use UqAt\Parser\Entity\AbstractLine as LineFabric;

final class FilterTest extends TestCase
{
    public function testOk(): void
    {
        $filter = new Filter();
        $filter->addToPool(LineFabric::fabric('C 1.1 8.15.1 P 15.10.2012 83'));
        $filter->addToPool(LineFabric::fabric('C 1 10.1 P 01.12.2012 65'));
        $filter->addToPool(LineFabric::fabric('C 1.1 5.5.1 P 01.11.2012 117'));
        $query  = LineFabric::fabric('D 1.1 8 P 01.01.2012-01.12.2012');
        $result = $filter->filter($query);
        self::assertCount(1, $result);
        self::assertSame(83, $result[0]->getWaitTime());
        $filter->addToPool(LineFabric::fabric('C 3 10.2 N 02.10.2012 100'));
        $query  = LineFabric::fabric('D 1 * P 08.10.2012-20.11.2012');
        $result = $filter->filter($query);
        self::assertCount(2, $result);
        self::assertSame(83, $result[0]->getWaitTime());
        self::assertSame(117, $result[1]->getWaitTime());
        $query  = LineFabric::fabric('D 3 10 P 01.12.2012');
        $result = $filter->filter($query);
        self::assertEmpty($result);
    }
}
