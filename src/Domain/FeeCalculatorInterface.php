<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain;

interface FeeCalculatorInterface
{
    /**
     * @return float the calculated total fee
     */
    public function calculate(LoanProposal $application): float;
}
