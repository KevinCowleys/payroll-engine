<?php

declare(strict_types=1);

namespace App\Domain\Payroll\DTOs;

use App\Domain\Payroll\ValueObjects\Money;

final readonly class PayrollCalculationResponse
{
    /**
     * @param list<AppliedRule> $appliedRules
     */
    public function __construct(
        public float $regularHours,
        public float $overtimeHours,
        public float $doubleTimeHours,
        public Money $regularPay,
        public Money $overtimePay,
        public Money $doubleTimePay,
        public Money $grossPay,
        public array $appliedRules,
    ) {
    }
}
