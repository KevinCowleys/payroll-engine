<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Payroll\DTOs\PayrollCalculationRequest;
use App\Domain\Payroll\Services\PayrollCalculationService;
use App\Http\Requests\CalculatePayrollRequest;
use Illuminate\Http\JsonResponse;

final class PayrollCalculationController extends Controller
{
    /**
     * @param PayrollCalculationService $service
     */
    public function __construct(
        private PayrollCalculationService $service,
    ) {
    }

    /**
     * @param CalculatePayrollRequest $request
     *
     * @return JsonResponse
     */
    public function __invoke(CalculatePayrollRequest $request): JsonResponse
    {
        $calculation = $this->service->calculate(
            new PayrollCalculationRequest(
                country: $request->string('country')->toString(),
                region: $request->string('region')->toString() ?: null,
                ruleType: $request->ruleType(),
                hoursWorked: $request->float('hours_worked'),
                hourlyRate: $request->float('hourly_rate'),
            ),
        );

        return response()->json($calculation->toArray());
    }
}
