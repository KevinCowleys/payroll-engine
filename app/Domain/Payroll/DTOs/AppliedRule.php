<?php

declare(strict_types=1);

namespace App\Domain\Payroll\DTOs;

use App\Domain\Payroll\Enums\RuleType;
use App\Domain\Payroll\Models\PayrollRule;

final readonly class AppliedRule
{
    public function __construct(
        public string $name,
        public RuleType $type,
        public int $threshold,
    ) {
    }

    public static function fromModel(PayrollRule $rule): self
    {
        return new self(
            name: $rule->name,
            type: $rule->rule_type,
            threshold: $rule->threshold,
        );
    }
}
