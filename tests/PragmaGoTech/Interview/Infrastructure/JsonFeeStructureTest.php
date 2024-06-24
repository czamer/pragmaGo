<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Infrastructure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use RuntimeException;

/**
 * @internal
 */
#[CoversClass(JsonFeeStructure::class)]
final class JsonFeeStructureTest extends TestCase
{
    private string $jsonFilePath;

    protected function setUp(): void
    {
        // @phpstan-ignore-next-line
        $this->jsonFilePath = realpath(PROJECT_ROOT_PATH . '/var/fee_structure.json');
    }

    public function testLoadValidJson(): void
    {
        $feeStructure = new JsonFeeStructure($this->jsonFilePath);
        $this->assertInstanceOf(JsonFeeStructure::class, $feeStructure);
    }

    public function testInvalidJsonFilePath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new JsonFeeStructure('invalid/path/to/fees.json');
    }

    public function testInvalidJsonData(): void
    {
        $invalidJson = '{invalid json}';
        $tmpFile = tmpfile();
        fwrite($tmpFile, $invalidJson);
        fseek($tmpFile, 0);

        $invalidJsonFilePath =   stream_get_meta_data($tmpFile)['uri'];
        //        var_dump($invalidJsonFilePath);die;
        $this->expectException(RuntimeException::class);
        new JsonFeeStructure($invalidJsonFilePath);
    }

    public function testInvalidLoanTerm(): void
    {
        $feeStructure = new JsonFeeStructure($this->jsonFilePath);
        $this->expectException(InvalidArgumentException::class);
        $feeStructure->getFees(36);
    }

    public function testGetFees(): void
    {
        $feeStructure = new JsonFeeStructure($this->jsonFilePath);
        $fees = $feeStructure->getFees(12);
        $this->assertIsArray($fees);
        $this->assertArrayHasKey(1000, $fees);
        $this->assertEquals(50, $fees[1000]);
    }
}
