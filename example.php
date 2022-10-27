<?php

declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

$input = <<<TESTSTRING
7
C 1.1 8.15.1 P 15.10.2012 83
C 1 10.1 P 01.12.2012 65
C 1.1 5.5.1 P 01.11.2012 117
D 1.1 8 P 01.01.2012-01.12.2012
C 3 10.2 N 02.10.2012 100
D 1 * P 08.10.2012-20.11.2012
D 3 10 P 01.12.2012
TESTSTRING;

$counter = new \UqAt\Output\AverageOutputCounter();
$filter  = new \UqAt\Filter\Filter();
foreach (explode("\n", $input) as $lineNum => $line) {
    if ($lineNum === 0) {
        continue;
    }
    $item = \UqAt\Parser\Entity\AbstractLine::fabric($line);
    if ($item->isWaitingTime()) {
        $filter->addToPool($item);
        continue;
    }
    $average = $counter->count(...$filter->filter($item));
    echo $average ?: '-';
    echo "\n";
}
