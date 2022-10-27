<?php

declare(strict_types=1);

namespace UqAt\Output;

use UqAt\Parser\Entity\WaitingTime;

final class AverageOutputCounter
{
    public function count(WaitingTime ...$waitingTime): int
    {
        if (count($waitingTime) === 0) {
            return 0;
        }
        $average = 0;
        foreach ($waitingTime as $time) {
            $average += $time->getWaitTime();
        }
        return (int)round($average / count($waitingTime), 0, PHP_ROUND_HALF_EVEN);
    }
}