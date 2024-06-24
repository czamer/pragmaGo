<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain;

use PragmaGoTech\Interview\Domain\Exception\AmountOutOfBoundException;
use PragmaGoTech\Interview\Domain\Exception\LoanAmountPrecisionException;
use PragmaGoTech\Interview\Domain\Exception\UnsupportedTermException;

final class LoanProposal
{
    private const float MIN_AMOUNT = 1000.00;
    private const float MAX_AMOUNT = 20000.00;
    private const array ALLOWED_TERMS = [12, 24];

    public function __construct(public readonly int $term, public readonly float $amount)
    {
        if (!in_array($term, self::ALLOWED_TERMS)) {
            throw new UnsupportedTermException();
        }

        if ($amount < self::MIN_AMOUNT || $amount > self::MAX_AMOUNT) {
            throw new AmountOutOfBoundException(
                'Amount must be between ' . self::MIN_AMOUNT . ' PLN and ' . self::MAX_AMOUNT . ' PLN.'
            );
        }

        if (round($amount, 2) !== $amount) {
            throw new LoanAmountPrecisionException();
        }
    }
}
