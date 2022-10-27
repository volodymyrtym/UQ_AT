<?php

declare(strict_types=1);

namespace UqAt\Parser\Exception;

final class LineParseException extends \Exception
{
    private function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(string $line, string $msg, ?string $errorDetail): self
    {
        $errorMsg = sprintf('Error. Wrong input line `%s`. Error: `%s`.', $line, $msg);
        if ($errorDetail) {
            $errorMsg .= ' Wrong part: ' . $errorDetail;
        }
        return new self($errorMsg);
    }
}