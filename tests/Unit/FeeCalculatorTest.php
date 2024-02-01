<?php

declare(strict_types=1);

namespace Unit;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Service\FeeCalculator;

class FeeCalculatorTest extends TestCase
{
    public function testCalculateFeeFor12MonthsTerm()
    {
        $proposal = $this->createMock(LoanProposal::class);
        $proposal->method('getTerm')->willReturn(12);
        $proposal->method('getAmount')->willReturn(19250.00);

        $calculator = new FeeCalculator();
        $fee = $calculator->calculate($proposal);
        $this->assertEquals(385, $fee);
    }

    public function testCalculateFeeFor24MonthsTerm()
    {
        $proposal = $this->createMock(LoanProposal::class);
        $proposal->method('getTerm')->willReturn(24);
        $proposal->method('getAmount')->willReturn(11500.00);

        $calculator = new FeeCalculator();
        $fee = $calculator->calculate($proposal);
        $this->assertEquals(460, $fee);
    }

    public function testExceptionForInvalidAmount()
    {
        $this->expectException(\InvalidArgumentException::class);

        $proposal = $this->createMock(LoanProposal::class);
        $proposal->method('getTerm')->willReturn(12);
        $proposal->method('getAmount')->willReturn(500.00);

        $calculator = new FeeCalculator();
        $calculator->calculate($proposal);
    }

    public function testExceptionForInvalidTerm()
    {
        $this->expectException(\InvalidArgumentException::class);

        $proposal = $this->createMock(LoanProposal::class);
        $proposal->method('getTerm')->willReturn(5);
        $proposal->method('getAmount')->willReturn(1500.00);

        $calculator = new FeeCalculator();
        $calculator->calculate($proposal);
    }
}
