<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Repositories;

use App\Domain\Payroll\Enums\RuleType;
use App\Domain\Payroll\Models\PayrollRule;

final class EloquentPayrollRuleRepository implements PayrollRuleRepositoryInterface
{
    /**
     * @param string $country
     * @param ?string $region
     *
     * @return ?PayrollRule
     */
    public function findApplicableRule(
        string $country,
        ?string $region,
        RuleType $ruleType,
    ): ?PayrollRule {
        return PayrollRule::query()
            ->where('country', $country)
            ->where('rule_type', $ruleType)
            ->where(function ($query) use ($region): void {
                $query
                    ->whereNull('region')
                    ->orWhere('region', $region);
            })
            ->whereDate('effective_from', '<=', today())
            ->where(function ($query): void {
                $query
                    ->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', today());
            })
            ->orderBy('priority')
            ->first();
    }
}
