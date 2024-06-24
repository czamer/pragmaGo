<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain;

interface FeeStructureInterface
{
    /**
     * @return array<float>
     */
    public function getFees(int $term): array;
}
