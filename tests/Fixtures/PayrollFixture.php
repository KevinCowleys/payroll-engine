<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use App\Domain\Payroll\DTOs\PayrollCalculationRequest;
use App\Domain\Payroll\Enums\Currency;
use App\Domain\Payroll\Enums\RuleType;
use App\Domain\Payroll\Models\PayrollRule;

final class PayrollFixture
{
    /**
     * @param float $hoursWorked = 8
     * @param float $hourlyRate = 20
     * @param string $country = 'US'
     * @param ?string $region = 'CA'
     * @param RuleType $ruleType = RuleType::Daily
     *
     * @return PayrollCalculationRequest
     */
    public static function request(
        float $hoursWorked = 8,
        float $hourlyRate = 20,
        string $country = 'US',
        ?string $region = 'CA',
        RuleType $ruleType = RuleType::Daily,
    ): PayrollCalculationRequest {
        return new PayrollCalculationRequest(
            country: $country,
            region: $region,
            ruleType: $ruleType,
            hoursWorked: $hoursWorked,
            hourlyRate: $hourlyRate,
        );
    }

    /**
     * @param RuleType $ruleType
     * @param string $name
     * @param int $threshold
     * @param float $overtimeMultiplier
     * @param Currency $currency
     * @param string $country
     * @param ?string $region
     *
     * @return PayrollRule
     */
    public static function rule(
        RuleType $ruleType,
        string $name,
        int $threshold,
        float $overtimeMultiplier,
        Currency $currency,
        string $country,
        ?string $region,
    ): PayrollRule {
        return new PayrollRule([
                'rule_type' => $ruleType,
                'name' => $name,
                'threshold' => $threshold,
                'overtime_multiplier' => $overtimeMultiplier,
                'currency' => $currency,
                'country' => $country,
                'region' => $region,
            ]);
    }

    /**
     * @param string $name = 'California Daily Overtime'
     * @param int $threshold = 8
     * @param float $overtimeMultiplier = 1.5
     * @param Currency $currency = Currency::USD
     * @param string $country = 'US'
     * @param ?string $region = 'CA'
     *
     * @return PayrollRule
     */
    public static function dailyRule(
        string $name = 'California Daily Overtime',
        int $threshold = 8,
        float $overtimeMultiplier = 1.5,
        Currency $currency = Currency::USD,
        string $country = 'US',
        ?string $region = 'CA',
    ): PayrollRule {
        return self::rule(
            ruleType: RuleType::Daily,
            name: $name,
            threshold: $threshold,
            overtimeMultiplier: $overtimeMultiplier,
            currency: $currency,
            country: $country,
            region: $region,
        );
    }

    /**
     * @param string $name = 'US Weekly Overtime'
     * @param int $threshold = 40
     * @param float $overtimeMultiplier = 1.5
     * @param Currency $currency = Currency::USD
     * @param string $country = 'US'
     * @param ?string $region = null
     *
     * @return PayrollRule
     */
    public static function weeklyRule(
        string $name = 'US Weekly Overtime',
        int $threshold = 40,
        float $overtimeMultiplier = 1.5,
        Currency $currency = Currency::USD,
        string $country = 'US',
        ?string $region = null,
    ): PayrollRule {
        return self::rule(
            ruleType: RuleType::Weekly,
            name: $name,
            threshold: $threshold,
            overtimeMultiplier: $overtimeMultiplier,
            currency: $currency,
            country: $country,
            region: $region,
        );
    }
}
