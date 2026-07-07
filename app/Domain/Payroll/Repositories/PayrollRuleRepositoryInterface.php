<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Repositories;

use App\Domain\Payroll\Models\PayrollRule;
use Illuminate\Support\Collection;

interface PayrollRuleRepositoryInterface
{
    /**
     * @return Collection<int, PayrollRule>
     */
    public function findApplicableRules(
        string $country,
        ?string $region,
    ): Collection;
}
