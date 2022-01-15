<?php

namespace application\Domain\Calculation;

class CalculationResponse
{
    /**
     * @var float
     */
    private $totalAmountToPay;

    /**
     * @var array
     */
    private $paymentSchedule;

    /**
     * @param float $totalAmountToPay
     */
    public function setTotalAmountToPay(float $totalAmountToPay)
    {
        $this->totalAmountToPay = $totalAmountToPay;
    }

    /**
     * @return array
     */
    public function getPaymentSchedule(): array
    {
        return $this->paymentSchedule;
    }

    /**
     * @param array $paymentSchedule
     */
    public function setPaymentSchedule (array $paymentSchedule)
    {
        $this->paymentSchedule = $paymentSchedule;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'totalAmountToPay' => $this->totalAmountToPay,
            'paymentSchedule' =>$this->paymentSchedule
        ];
    }
}