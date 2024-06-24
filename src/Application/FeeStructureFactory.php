<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Application;

use PragmaGoTech\Interview\Domain\FeeStructureInterface;
use PragmaGoTech\Interview\Infrastructure\JsonFeeStructure;

final class FeeStructureFactory
{
    public static function createFromJson(string $filePath): FeeStructureInterface
    {
        return new JsonFeeStructure($filePath);
    }
}
