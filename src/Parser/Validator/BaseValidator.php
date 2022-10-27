<?php

declare(strict_types=1);

namespace UqAt\Parser\Validator;

use UqAt\Parser\Exception\LineParseException;

class BaseValidator
{
    /**
     * @throws LineParseException
     */
    public static function validate(array $data, string $line): void
    {
        [, $serviceAndVariation, $questionCatSubCat, $respType] = $data;
        if (!in_array($respType, ['N', 'P'], true)) {
            throw LineParseException::create('Response type must be N or P', $line, $respType);
        }
        if ($serviceAndVariation !== '*' && !self::stringContainsOnlyPositiveInt($serviceAndVariation)) {
            throw LineParseException::create("The serviceAndVariation are wrong", $line, $serviceAndVariation);
        }
        if ($questionCatSubCat !== '*'
            && !self::stringContainsOnlyPositiveInt($questionCatSubCat)) {
            throw LineParseException::create("The questionCatSubCat are wrong", $line, $questionCatSubCat);
        }
    }

    private static function stringContainsOnlyPositiveInt(string $input): bool
    {
        $result = explode('.', $input);

        return count($result) === count(
                array_filter($result, static function ($val) {
                    return (int)$val > 0;
                })
            );
    }
}