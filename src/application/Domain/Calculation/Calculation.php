<?php

namespace application\Domain\Calculation;

class Calculation
{
    /**
     * @var int
     */
    private $value;
    /**
     * @var int
     */
    private $years;
    /**
     * @var float
     */
    private $percent;

    /**
     * Calculation constructor.
     * @param int $value
     * @param int $years
     * @param int $percent
     * @param $paymentsInYear
     */
    public function __construct(int $value, int $years, int $percent, $paymentsInYear)
    {
        $this->value = $value;
        $this->years = $years;
        $this->percent = $percent / 100;
        $this->paymentsInYear = $paymentsInYear;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getYears(): int
    {
        return $this->years;
    }

    /**
     * @param int $years
     */
    public function setYears(int $years): void
    {
        $this->years = $years;
    }

    /**
     * @return string
     */
    public function getPercent(): string
    {
        return $this->percent;
    }

    /**
     * @param string $percent
     */
    public function setPercent(string $percent): void
    {
        $this->percent = $percent;
    }

    /**
     * @return int
     */
    public function getPaymentsInYear(): int
    {
        return $this->paymentsInYear;
    }

    /**
     * @param int $paymentsInYear
     */
    public function setPaymentsInYear(int $paymentsInYear): void
    {
        $this->paymentsInYear = $paymentsInYear;
    }
    /**
     * @var int
     */
    private $paymentsInYear;




}