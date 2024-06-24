<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Domain;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Application\FeeStructureFactory;
use PragmaGoTech\Interview\Domain\Exception\AmountOutOfBoundException;
use PragmaGoTech\Interview\Domain\Exception\UnsupportedTermException;

/**
 * @internal
 */
#[CoversClass(FeeCalculator::class)]
final class FeeCalculatorTest extends TestCase
{
    private FeeStructureInterface $feeStructureProvider;

    protected function setUp(): void
    {
        /** @var string $feeStructureFilePath */
        $feeStructureFilePath = realpath(PROJECT_ROOT_PATH . '/var/fee_structure.json');
        $this->feeStructureProvider = FeeStructureFactory::createFromJson($feeStructureFilePath);
    }

    #[DataProvider('feeDataProviderFor12MonthTerm')]
    public function testCalculateFeeFor12MonthTerm(float $amount, float $expectedFee): void
    {
        $calculator = new FeeCalculator($this->feeStructureProvider);
        $proposal = new LoanProposal(12, $amount);
        $fee = $calculator->calculate($proposal);
        $this->assertEquals($expectedFee, $fee);
    }

    /**
     * @return array<int, float[]>
     */
    public static function feeDataProviderFor12MonthTerm(): array
    {
        return [
            [1000, 50],
            [2000, 90],
            [1500, 70],  // Interpolated and rounded up
            [19250, 385], // Interpolated and rounded up
        ];
    }

    #[DataProvider('feeDataProviderFor24MonthTerm')]
    public function testCalculateFeeFor24MonthTerm(float $amount, float $expectedFee): void
    {
        $calculator = new FeeCalculator($this->feeStructureProvider);
        $proposal = new LoanProposal(24, $amount);
        $fee = $calculator->calculate($proposal);
        $this->assertEquals($expectedFee, $fee);
    }

    /**
     * @return array<int, float[]>
     */
    public static function feeDataProviderFor24MonthTerm(): array
    {
        return [
            [1000, 70],
            [2000, 100],
            [1500, 85],  // Interpolated and rounded up
            [2750, 115], // Interpolated and rounded up
            [11500, 460], // Interpolated and rounded up
        ];
    }

    public function testInvalidTermThrowsException(): void
    {
        $calculator = new FeeCalculator($this->feeStructureProvider);
        $this->expectException(UnsupportedTermException::class);
        $proposal = new LoanProposal(36, 5000);
        $calculator->calculate($proposal);
    }

    public function testAmountOutOfBoundsThrowsException(): void
    {
        $calculator = new FeeCalculator($this->feeStructureProvider);

        $this->expectException(AmountOutOfBoundException::class);
        $proposal = new LoanProposal(12, 999);
        $calculator->calculate($proposal);

        $this->expectException(AmountOutOfBoundException::class);
        $proposal = new LoanProposal(24, 20001);
        $calculator->calculate($proposal);
    }
}
