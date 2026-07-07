<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Payroll\Enums\Currency;
use App\Domain\Payroll\Enums\RuleType;
use App\Domain\Payroll\Models\PayrollRule;
use Illuminate\Database\Seeder;

class PayrollRuleSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $effectiveFrom = today();

        PayrollRule::query()->insert([
            [
                'country' => 'US',
                'region' => null,
                'name' => 'US Federal Weekly Overtime',
                'rule_type' => RuleType::Weekly->value,
                'threshold' => 40,
                'overtime_multiplier' => 1.50,
                'currency' => Currency::USD->value,
                'priority' => 100,
                'effective_from' => $effectiveFrom,
                'effective_to' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'country' => 'US',
                'region' => 'CA',
                'name' => 'California Daily Overtime',
                'rule_type' => RuleType::Daily->value,
                'threshold' => 8,
                'overtime_multiplier' => 1.50,
                'currency' => Currency::USD->value,
                'priority' => 20,
                'effective_from' => $effectiveFrom,
                'effective_to' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'country' => 'DK',
                'region' => null,
                'name' => 'Denmark Standard Work Week',
                'rule_type' => RuleType::Weekly->value,
                'threshold' => 37,
                'overtime_multiplier' => 1.50,
                'currency' => Currency::DKK->value,
                'priority' => 100,
                'effective_from' => $effectiveFrom,
                'effective_to' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
