<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain;

use PragmaGoTech\Interview\Domain\Exception\UnsupportedTermException;
use InvalidArgumentException;

final readonly class FeeCalculator implements FeeCalculatorInterface
{
    public function __construct(private FeeStructureInterface $feeStructure) {}

    public function calculate(LoanProposal $proposal): float
    {
        try {
            $fees = $this->feeStructure->getFees($proposal->term);
        } catch (InvalidArgumentException $exception) {
            throw new UnsupportedTermException();
        }

        $fee = $this->interpolateFee($proposal->amount, $fees);

        return $this->roundUpFee($fee, $proposal->amount);
    }

    /**
     * @param float[] $fees
     */
    private function interpolateFee(float $amount, array $fees): float
    {
        $lowerBound = null;
        $upperBound = null;

        foreach ($fees as $breakpoint => $fee) {
            $breakpoint = (float) $breakpoint;

            if ($amount === $breakpoint) {
                return $fee;
            }

            if ($amount > $breakpoint) {
                $lowerBound = $breakpoint;
            } elseif ($amount < $breakpoint) {
                $upperBound = $breakpoint;

                break;
            }
        }

        if (null === $lowerBound || null === $upperBound) {
            throw new UnsupportedTermException();
        }

        $lowerFee = $fees[$lowerBound];
        $upperFee = $fees[$upperBound];

        return $lowerFee + ($upperFee - $lowerFee) * (($amount - $lowerBound) / ($upperBound - $lowerBound));
    }

    private function roundUpFee(float $fee, float $amount): float
    {
        $totalAmount = $amount + $fee;
        $roundedTotal = ceil($totalAmount / 5) * 5;

        return $roundedTotal - $amount;
    }
}
