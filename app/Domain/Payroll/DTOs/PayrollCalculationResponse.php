<?php

declare(strict_types=1);

namespace App\Domain\Payroll\DTOs;

use App\Domain\Payroll\ValueObjects\Money;

final readonly class PayrollCalculationResponse
{
    public function __construct(
        public float $regularHours,
        public float $overtimeHours,
        public Money $regularPay,
        public Money $overtimePay,
        public Money $grossPay,
        public AppliedRule $appliedRule,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'regular_hours' => $this->regularHours,
            'overtime_hours' => $this->overtimeHours,
            'regular_pay' => $this->regularPay->toDecimal(),
            'overtime_pay' => $this->overtimePay->toDecimal(),
            'gross_pay' => $this->grossPay->toDecimal(),
            'currency' => $this->grossPay->currency()->value,
            'rule' => [
                'name' => $this->appliedRule->name,
                'type' => $this->appliedRule->type->value,
                'threshold' => $this->appliedRule->threshold,
            ],
        ];
    }
}
