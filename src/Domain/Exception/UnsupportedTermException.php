<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Exception;

use InvalidArgumentException;

final class UnsupportedTermException extends InvalidArgumentException
{
    // @phpstan-ignore-next-line
    protected $message = 'Term must be either 12 or 24 months.';
}
