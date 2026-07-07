<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Services;

use App\Domain\Payroll\DTOs\PayrollCalculationRequest;
use App\Domain\Payroll\DTOs\PayrollCalculationResponse;
use App\Domain\Payroll\Exceptions\PayrollRuleNotFoundException;
use App\Domain\Payroll\Repositories\PayrollRuleRepositoryInterface;

final readonly class PayrollCalculationService
{
    /**
     * @param PayrollRuleRepositoryInterface $repository
     * @param CalculatorResolver $resolver
     */
    public function __construct(
        private PayrollRuleRepositoryInterface $repository,
        private CalculatorResolver $resolver,
    ) {
    }

    /**
     * Used to calculate the payroll based
     * on the incoming request.
     *
     * @param PayrollCalculationRequest $request
     *
     * @return PayrollCalculationResponse
     */
    public function calculate(PayrollCalculationRequest $request): PayrollCalculationResponse
    {
        $rule = $this->repository->findApplicableRule(
            $request->country,
            $request->region,
            $request->ruleType,
        );

        if ($rule === null) {
            throw PayrollRuleNotFoundException::forLocation(
                $request->country,
                $request->region,
            );
        }

        $calculator = $this->resolver->resolve(
            $request->ruleType,
        );

        return $calculator->calculate($request, $rule);
    }
}
