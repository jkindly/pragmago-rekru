<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service;

use PragmaGoTech\Interview\LoanTermFee;
use PragmaGoTech\Interview\Model\LoanProposal;

class FeeCalculator implements FeeCalculatorInterface
{
    private const int MIN_AMOUNT = 1000;

    private const int MAX_AMOUNT = 20000;

    private const int ONE_YEAR_IN_MONTHS = 12;

    private const int TWO_YEARS_IN_MONTHS = 24;

    #[\Override]
    public function calculate(LoanProposal $application): float
    {
        $term = $application->getTerm();
        $amount = $application->getAmount();

        if ($amount < self::MIN_AMOUNT || $amount > self::MAX_AMOUNT) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid amount. Amount must be between %d and %d.',
                self::MIN_AMOUNT,
                self::MAX_AMOUNT
            ));
        }

        if ($term < self::ONE_YEAR_IN_MONTHS || $term > self::TWO_YEARS_IN_MONTHS) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid term. Term must be %d or %d.',
                self::ONE_YEAR_IN_MONTHS,
                self::TWO_YEARS_IN_MONTHS
            ));
        }

        $feeStructure = $term === self::ONE_YEAR_IN_MONTHS ? LoanTermFee::TERM_12 : LoanTermFee::TERM_24;

        $fee = $this->interpolateFee($amount, $feeStructure);

        $totalAmount = $amount + $fee;
        $rest = $totalAmount % 5;
        if ($rest > 0) {
            $fee += 5 - $rest;
        }

        return $fee;
    }

    private function interpolateFee(float $amount, array $feeStructure): float
    {
        $lowerBound = null;
        $upperBound = null;
        foreach ($feeStructure as $structureAmount => $structureFee) {
            if ($amount <= $structureAmount) {
                $upperBound = $structureAmount;
                break;
            }

            $lowerBound = $structureAmount;
        }

        if (null === $lowerBound || null === $upperBound) {
            throw new \InvalidArgumentException('Invalid fee structure.');
        }

        $lowerBoundFee = $feeStructure[$lowerBound];
        $upperBoundFee = $feeStructure[$upperBound];

        $fraction = ($amount - $lowerBound) / ($upperBound - $lowerBound);

        return round($lowerBoundFee + ($upperBoundFee - $lowerBoundFee) * $fraction, 2);
    }
}
