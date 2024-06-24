<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain\Exception;

use InvalidArgumentException;

final class LoanAmountPrecisionException extends InvalidArgumentException
{
    // @phpstan-ignore-next-line
    protected $message = 'Amount must have at most two decimal places.';
}
