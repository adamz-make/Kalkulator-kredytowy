<?php

namespace App\application\Application\Service\Calculation;

use App\application\Application\Service\Calculation\Exceptions\UnknowInstallmentType;
use application\Domain\Calculation\Interfaces\CalculationServiceInterface;

abstract class AbstractCalculationService
{
    private const FIXED_INSTALLMENT = 'fixed';
    private const DECREASING_INSTALLMENT = 'decreasing';

    /**
     * @param string $installmentType
     * @return CalculationServiceInterface
     * @throws UnknowInstallmentType
     */
    static public function getService(string $installmentType): CalculationServiceInterface {
        switch ($installmentType) {
            case self::DECREASING_INSTALLMENT:
                return new CalculateDecreasingInstallmentsService();
            case self::FIXED_INSTALLMENT:
                return new CalculateFixedInstallmentsService();
            default:
                throw new UnknowInstallmentType();
        }

    }

}