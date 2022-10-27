<?php

declare(strict_types=1);

namespace UqAt\Filter;

use UqAt\Parser\Entity\Query;
use UqAt\Parser\Entity\WaitingTime;

final class Filter implements FilterInterface
{
    /** @var WaitingTime[] $pool */
    private array $pool     = [];
    private array $poolKeys = [];
    private array $indexQuestion;
    private array $indexCategory;
    private array $indexSubcategory;
    private array $indexService;
    private array $indexVariation;
    private array $indexDateY;

    public function addToPool(WaitingTime $waitingTime): void
    {
        $key = count($this->pool);
        $this->pool[]     = $waitingTime;
        $this->poolKeys[] = $key;
        $this->setPoolIndexes($waitingTime, $key);
    }

    public function filter(Query $query): array
    {
        $ids = $this->poolKeys;
        if (!$query->isAnyService()) {
            $ids = self::matchedKeys($ids, $this->fetchByService($query));
            if (empty($ids)) {
                return [];
            }
        }

        if (!$query->isAnyQuestion()) {
            $ids = self::matchedKeys($ids, $this->fetchByQuestion($query));
            if (empty($ids)) {
                return [];
            }
        }

        return $this->fetchByDate($query, $ids);
    }

    private function setPoolIndexes(WaitingTime $item, int $key): void
    {
        $this->indexService[$item->getService()][] = $key;
        if ($item->getVariation()) {
            $variationKey                          = $item->getService() . '_' . $item->getVariation();
            $this->indexVariation[$variationKey][] = $key;
        }
        $this->indexQuestion[$item->getQuestion()][] = $key;
        if ($item->getCategory()) {
            $categoryKey                         = $item->getQuestion() . '_' . $item->getCategory();
            $this->indexCategory[$categoryKey][] = $key;
            if ($item->getSubCategory()) {
                $subcategoryKey                            = $categoryKey . '_' . $item->getSubCategory();
                $this->indexSubcategory[$subcategoryKey][] = $key;
            }
        }

        $this->indexDateY[$item->getDate()->format('Y')][] = $key;
    }

    /**
     * @param Query $query
     * @param int[] $filteredResults
     *
     * @return WaitingTime[]
     */
    private function fetchByDate(Query $query, array $filteredResults): array
    {
        $ids    = array_intersect_key(
            $this->idsWithAcceptableYear($query),
            $filteredResults
        ); //we don`t want to loop over already filtered resuts
        $result = [];
        foreach ($ids as $id) {
            $waitingTime = $this->getWaitingTime($id);
            if (self::isWaitingTimeInRangeQuery($waitingTime, $query)) {
                $result[] = $waitingTime;
            }
        }

        return $result;
    }

    private function idsWithAcceptableYear(Query $query): array
    {
        $dateRange = range($query->getFrom()->format('Y'), $query->getTo()->format('Y'));
        $result    = [];
        foreach ($dateRange as $acceptableYear) {
            $result[] = $this->indexDateY[$acceptableYear] ?? [];
        }

        return array_merge(...$result);
    }

    private function getWaitingTime(int $key): WaitingTime
    {
        return $this->pool[$key];
    }

    private static function isWaitingTimeInRangeQuery(
        WaitingTime $waitingTime,
        Query $query
    ): bool {
        $date = $waitingTime->getDate();

        return $date > $query->getFrom() && $date < $query->getTo();
    }

    private function fetchByService(Query $query): array
    {
        if ($query->getVariation()) {
            $idsKey = $query->getService() . '_' . $query->getVariation();

            return $this->indexVariation[$idsKey] ?? [];
        }

        return $this->indexService[$query->getService()] ?? [];
    }

    private function fetchByQuestion(Query $query): array
    {
        if ($query->getSubCategory()) {
            $idsKey = $query->getQuestion() . '_' . $query->getCategory() . '_' . $query->getSubCategory();

            return $this->indexSubcategory[$idsKey] ?? [];
        }
        if ($query->getCategory()) {
            $idsKey = $query->getQuestion() . '_' . $query->getCategory();

            return $this->indexCategory[$idsKey] ?? [];
        }

        return $this->indexQuestion[$query->getQuestion()] ?? [];
    }

    private static function matchedKeys(array $stack1, array $stack2): array
    {
        return array_intersect($stack1, $stack2);
    }
}