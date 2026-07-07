<?php

declare(strict_types=1);

use App\Domain\Payroll\Calculators\WeeklyOvertimeCalculator;
use App\Domain\Payroll\Enums\RuleType;
use Tests\Fixtures\PayrollFixture;

it('calculates pay with no overtime', function (): void {
    $calculator = new WeeklyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 40,
            hourlyRate: 20,
            ruleType: RuleType::Weekly,
        ),
        PayrollFixture::weeklyRule(),
    );

    expect($response->regularHours)->toBe(40.0);
    expect($response->overtimeHours)->toBe(0.0);
    expect($response->doubleTimeHours)->toBe(0.0);
    expect($response->regularPay->toDecimal())->toBe(800.00);
    expect($response->overtimePay->toDecimal())->toBe(0.00);
    expect($response->doubleTimePay->toDecimal())->toBe(0.00);
    expect($response->grossPay->toDecimal())->toBe(800.00);
});

it('calculates weekly overtime', function (): void {
    $calculator = new WeeklyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 45,
            hourlyRate: 20,
            ruleType: RuleType::Weekly,
        ),
        PayrollFixture::weeklyRule(),
    );

    expect($response->regularHours)->toBe(40.0);
    expect($response->overtimeHours)->toBe(5.0);
    expect($response->regularPay->toDecimal())->toBe(800.00);
    expect($response->overtimePay->toDecimal())->toBe(150.00);
    expect($response->grossPay->toDecimal())->toBe(950.00);
});

it('calculates exactly at the overtime threshold', function (): void {
    $calculator = new WeeklyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 40,
            hourlyRate: 25,
            ruleType: RuleType::Weekly,
        ),
        PayrollFixture::weeklyRule(),
    );

    expect($response->regularHours)->toBe(40.0);
    expect($response->overtimeHours)->toBe(0.0);
    expect($response->regularPay->toDecimal())->toBe(1000.00);
    expect($response->overtimePay->toDecimal())->toBe(0.00);
    expect($response->grossPay->toDecimal())->toBe(1000.00);
});

it('calculates zero hours correctly', function (): void {
    $calculator = new WeeklyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 0,
            ruleType: RuleType::Weekly,
        ),
        PayrollFixture::weeklyRule(),
    );

    expect($response->regularHours)->toBe(0.0);
    expect($response->overtimeHours)->toBe(0.0);
    expect($response->grossPay->toDecimal())->toBe(0.00);
});

it('includes the applied rule in the response', function (): void {
    $calculator = new WeeklyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 45,
            ruleType: RuleType::Weekly,
        ),
        PayrollFixture::weeklyRule(
            name: 'US Weekly Overtime',
        ),
    );

    expect($response->appliedRule->name)->toBe('US Weekly Overtime');
    expect($response->appliedRule->type)->toBe(RuleType::Weekly);
    expect($response->appliedRule->threshold)->toBe(40);
});

it('rounds overtime pay to the nearest cent', function (): void {
    $calculator = new WeeklyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 41,
            hourlyRate: 19.99,
            ruleType: RuleType::Weekly,
        ),
        PayrollFixture::weeklyRule(),
    );

    expect($response->overtimePay->toDecimal())->toBe(29.99);
});
