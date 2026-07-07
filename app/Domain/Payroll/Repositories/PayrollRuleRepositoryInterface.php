<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Repositories;

use App\Domain\Payroll\Enums\RuleType;
use App\Domain\Payroll\Models\PayrollRule;

interface PayrollRuleRepositoryInterface
{
    /**
     * @return ?PayrollRule
     */
    public function findApplicableRule(
        string $country,
        ?string $region,
        RuleType $ruleType,
    ): ?PayrollRule;
}
