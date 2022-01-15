<?php

namespace application\Domain\Calculation\Interfaces;

use application\Domain\Calculation\Calculation;
use application\Domain\Calculation\CalculationResponse;

interface CalculationServiceInterface
{
    public function execute (Calculation $calculation): CalculationResponse;

}