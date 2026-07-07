<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Calculators;

use App\Domain\Payroll\DTOs\PayrollCalculationRequest;
use App\Domain\Payroll\DTOs\PayrollCalculationResponse;
use App\Domain\Payroll\Models\PayrollRule;

interface OvertimeCalculatorInterface
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
    ): PayrollCalculationResponse;
}
