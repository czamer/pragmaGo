<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Infrastructure;

use PragmaGoTech\Interview\Domain\FeeStructureInterface;
use InvalidArgumentException;
use RuntimeException;

final class JsonFeeStructure implements FeeStructureInterface
{
    /**
     * @var array<int, float[]>
     */
    private array $feeStructure;

    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("File not found: {$filePath}");
        }

        /** @var string $json */
        $json = file_get_contents($filePath);
        $decoded = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException("Invalid JSON data in file: {$filePath}");
        }

        // @phpstan-ignore-next-line
        $this->feeStructure = $decoded;

        $this->castFeesToFloat();
    }

    private function castFeesToFloat(): void
    {
        foreach ($this->feeStructure as $term => $fees) {
            foreach ($fees as $amount => $fee) {
                $this->feeStructure[$term][$amount] = (float) $fee;
            }
        }
    }

    /**
     * @return float[]
     */
    public function getFees(int $term): array
    {
        if (!isset($this->feeStructure[$term])) {
            throw new InvalidArgumentException("No fee structure defined for the term: {$term}.");
        }

        return $this->feeStructure[$term];
    }
}
