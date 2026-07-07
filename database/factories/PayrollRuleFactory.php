<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Payroll\Enums\Currency;
use App\Domain\Payroll\Enums\RuleType;
use App\Domain\Payroll\Models\PayrollRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PayrollRule>
 */
class PayrollRuleFactory extends Factory
{
    protected $model = PayrollRule::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country' => 'US',
            'region' => null,
            'rule_type' => RuleType::Weekly,
            'name' => fake()->sentence(2),
            'threshold' => 40,
            'overtime_multiplier' => 1.5,
            'currency' => Currency::USD,
            'priority' => 100,
            'effective_from' => now()->subDay(),
            'effective_to' => null,
        ];
    }

    public function weekly(): static
    {
        return $this->state(fn () => [
            'rule_type' => RuleType::Weekly,
            'threshold' => 40,
            'overtime_multiplier' => 1.5,
        ]);
    }

    public function daily(): static
    {
        return $this->state(fn () => [
            'rule_type' => RuleType::Daily,
            'threshold' => 8,
            'overtime_multiplier' => 1.5,
        ]);
    }

    public function doubleTime(): static
    {
        return $this->state(fn () => [
            'rule_type' => RuleType::Daily,
            'threshold' => 12,
            'overtime_multiplier' => 2.0,
        ]);
    }

    public function forCountry(string $country): static
    {
        return $this->state(fn () => [
            'country' => $country,
        ]);
    }

    public function forRegion(?string $region): static
    {
        return $this->state(fn () => [
            'region' => $region,
        ]);
    }

    public function forCalifornia(): static
    {
        return $this->state(fn () => [
            'country' => 'US',
            'region' => 'CA',
            'currency' => Currency::USD,
        ]);
    }

    public function forDenmark(): static
    {
        return $this->state(fn () => [
            'country' => 'DK',
            'region' => null,
            'currency' => Currency::DKK,
        ]);
    }
}
