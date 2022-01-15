<?php

namespace App\application\Application\Service\Calculation;

use application\Domain\Calculation\Calculation;
use application\Domain\Calculation\CalculationResponse;
use application\Domain\Calculation\Interfaces\CalculationServiceInterface;

class CalculateFixedInstallmentsService implements CalculationServiceInterface
{
    public function execute (Calculation $calculation): CalculationResponse
    {
        $calculationResponse = new CalculationResponse();
        $totalNumberOfInstallments = $calculation->getYears() * $calculation->getPaymentsInYear();

        $calculatedPercent = 1 + ($calculation->getPercent() / $calculation->getPaymentsInYear());
        $installment = $calculation->getValue() * $calculatedPercent ** $totalNumberOfInstallments *
            ($calculatedPercent - 1) / ($calculatedPercent ** $totalNumberOfInstallments -1);

        $calculationResponse->setTotalAmountToPay(round ($installment * $calculation->getYears() * $calculation->getPaymentsInYear(),2));
        $calculationResponse->setPaymentSchedule($this->createPaymentSchedule($calculation, $installment));

        return $calculationResponse;
    }

    /**
     * @param Calculation $calculation
     * @param float $installment
     * @return array
     */
    private function createPaymentSchedule(Calculation $calculation, float $installment) : array
    {
        $numberOfPayments = $calculation->getPaymentsInYear() * $calculation->getYears();

        $paymentSchedule =[];
        $capitalToPay = $calculation->getValue();
        for ($i = 0; $i < $numberOfPayments; $i++) {
            $interestInstallment = round(($capitalToPay * $calculation->getPercent() * 30.416 / 365),2);
            $capitalInstallment = round($installment - $interestInstallment, 2);
            $paymentSchedule[$i] = [
                'index' => $i + 1,
                'installment' => round($installment, 2),
                'capitalInstallment' => $capitalInstallment,
                'interestInstallment' => $interestInstallment
            ];
            $capitalToPay = round ($capitalToPay - $capitalInstallment, 2);
        }

        return $paymentSchedule;
    }

}