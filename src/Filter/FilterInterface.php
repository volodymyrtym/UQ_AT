<?php

declare(strict_types=1);

namespace UqAt\Filter;

use UqAt\Parser\Entity\Query;
use UqAt\Parser\Entity\WaitingTime;

interface FilterInterface
{
    /**
     * @param Query $query
     *
     * @return WaitingTime[]
     */
    public function filter(Query $query): array;
}