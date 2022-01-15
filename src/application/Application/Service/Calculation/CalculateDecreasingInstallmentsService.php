<?php

namespace App\application\Application\Service\Calculation;

use application\Domain\Calculation\Calculation;
use application\Domain\Calculation\CalculationResponse;
use application\Domain\Calculation\Interfaces\CalculationServiceInterface;

class CalculateDecreasingInstallmentsService implements CalculationServiceInterface
{
    public function execute (Calculation $calculation): CalculationResponse
    {
        $installmentsData = [];
        $capital = $calculation->getValue() / ($calculation->getPaymentsInYear() * $calculation->getYears());
        $decreasingInterest = $calculation->getValue() * $calculation->getPercent() / $calculation->getPaymentsInYear();
        $paidCapital = $capital;
        $totalInterest = 0;
        $totalInterest += $decreasingInterest;
        $insallmentDecreasedAccumulated = 0;
        $insallmentDecreasedAccumulated += $decreasingInterest;
        $installmentsData[1] = [
            'index' => 1,
            'installment' => round($capital + $decreasingInterest, 2),
            'capitalInstallment' => round($capital, 2),
            'interestInstallment' => round($decreasingInterest, 2)
        ];
        $totalAmountToPay = $capital + $decreasingInterest;

        for ($i = 2; $i <= $calculation->getPaymentsInYear() * $calculation->getYears(); $i++) {
            $capital = $calculation->getValue() / ($calculation->getPaymentsInYear() * $calculation->getYears());
            $decreasingInterest = ($calculation->getValue() - $paidCapital) * $calculation->getPercent() / $calculation->getPaymentsInYear();
            $installmentDecreasing = $capital + $decreasingInterest;
            $paidCapital += $capital;
            $totalInterest += $decreasingInterest;
            $insallmentDecreasedAccumulated += $installmentDecreasing;

            $installmentsData[$i] = [
                'index' => $i,
                'installment' => round($capital + $decreasingInterest, 2),
                'capitalInstallment' => round($capital, 2),
                'interestInstallment' => round($decreasingInterest, 2)
            ];
            $totalAmountToPay += $capital + $decreasingInterest;
        }

        $calculationResponse = new CalculationResponse();
        $calculationResponse->setTotalAmountToPay($totalAmountToPay);
        $calculationResponse->setPaymentSchedule($installmentsData);
        return $calculationResponse;

    }

}