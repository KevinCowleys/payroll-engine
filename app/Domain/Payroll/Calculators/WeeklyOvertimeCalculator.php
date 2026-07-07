<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Calculators;

use App\Domain\Payroll\DTOs\AppliedRule;
use App\Domain\Payroll\DTOs\PayrollCalculationRequest;
use App\Domain\Payroll\DTOs\PayrollCalculationResponse;
use App\Domain\Payroll\Models\PayrollRule;
use App\Domain\Payroll\ValueObjects\Money;

final readonly class WeeklyOvertimeCalculator implements OvertimeCalculatorInterface
{
    /**
     * @param PayrollCalculationRequest $request
     * @param PayrollRule $rule
     *
     * @return PayrollCalculationResponse
     */
    public function calculate(
        PayrollCalculationRequest $request,
        PayrollRule $rule,
    ): PayrollCalculationResponse {
        $regularHours = min(
            $request->hoursWorked,
            $rule->threshold,
        );

        $overtimeHours = max(
            0,
            $request->hoursWorked - $rule->threshold,
        );

        $regularPay = Money::fromDecimal(
            $regularHours * $request->hourlyRate,
            $rule->currency,
        );

        $overtimePay = Money::fromDecimal(
            $overtimeHours
                * $request->hourlyRate
                * (float) $rule->overtime_multiplier,
            $rule->currency,
        );

        $doubleTimePay = Money::fromDecimal(
            0,
            $rule->currency,
        );

        return new PayrollCalculationResponse(
            regularHours: $regularHours,
            overtimeHours: $overtimeHours,
            doubleTimeHours: 0,
            regularPay: $regularPay,
            overtimePay: $overtimePay,
            doubleTimePay: $doubleTimePay,
            grossPay: $regularPay
                ->add($overtimePay)
                ->add($doubleTimePay),
            appliedRule: AppliedRule::fromModel($rule),
        );
    }
}
