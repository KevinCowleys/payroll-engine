<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Repositories;

use App\Domain\Payroll\Models\PayrollRule;
use Illuminate\Database\Eloquent\Collection;

final class EloquentPayrollRuleRepository implements PayrollRuleRepositoryInterface
{
    /**
     * @return Collection<int, PayrollRule>
     */
    public function findApplicableRules(
        string $country,
        ?string $region,
    ): Collection {
        return PayrollRule::query()
            ->where('country', $country)
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
            ->get();
    }
}
