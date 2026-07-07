<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Payroll\Enums\Currency;
use App\Domain\Payroll\Enums\RuleType;
use App\Models\PayrollRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PayrollRule>
 */
class PayrollRuleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country' => 'US',
            'region' => null,
            'rule_type' => RuleType::Weekly,
            'name' => '',
            'threshold' => 40,
            'overtime_multiplier' => 1.5,
            'currency' => Currency::USD,
            'priority' => '',
            'effective_from' => now()->subYear()->toDateString(),
            'effective_to' => null,
        ];
    }
}
