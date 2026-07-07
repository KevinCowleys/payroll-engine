<?php

declare(strict_types=1);

use App\Domain\Payroll\Calculators\DailyOvertimeCalculator;
use App\Domain\Payroll\Enums\RuleType;
use Tests\Fixtures\PayrollFixture;

it('calculates pay with no overtime', function (): void {
    $calculator = new DailyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 8,
            hourlyRate: 20,
            ruleType: RuleType::Daily,
        ),
        PayrollFixture::dailyRule(),
    );

    expect($response->regularHours)->toBe(8.0);
    expect($response->overtimeHours)->toBe(0.0);
    expect($response->doubleTimeHours)->toBe(0.0);
    expect($response->regularPay->toDecimal())->toBe(160.00);
    expect($response->overtimePay->toDecimal())->toBe(0.00);
    expect($response->doubleTimePay->toDecimal())->toBe(0.00);
    expect($response->grossPay->toDecimal())->toBe(160.00);
});

it('calculates daily overtime', function (): void {
    $calculator = new DailyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 10,
            hourlyRate: 20,
            ruleType: RuleType::Daily,
        ),
        PayrollFixture::dailyRule(),
    );

    expect($response->regularHours)->toBe(8.0);
    expect($response->overtimeHours)->toBe(2.0);
    expect($response->regularPay->toDecimal())->toBe(160.00);
    expect($response->overtimePay->toDecimal())->toBe(60.00);
    expect($response->grossPay->toDecimal())->toBe(220.00);
});

it('calculates exactly at the overtime threshold', function (): void {
    $calculator = new DailyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 8,
            hourlyRate: 25,
            ruleType: RuleType::Daily,
        ),
        PayrollFixture::dailyRule(),
    );

    expect($response->regularHours)->toBe(8.0);
    expect($response->overtimeHours)->toBe(0.0);
    expect($response->regularPay->toDecimal())->toBe(200.00);
    expect($response->overtimePay->toDecimal())->toBe(0.00);
    expect($response->grossPay->toDecimal())->toBe(200.00);
});

it('calculates zero hours correctly', function (): void {
    $calculator = new DailyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 0,
            ruleType: RuleType::Daily,
        ),
        PayrollFixture::dailyRule(),
    );

    expect($response->regularHours)->toBe(0.0);
    expect($response->overtimeHours)->toBe(0.0);
    expect($response->grossPay->toDecimal())->toBe(0.00);
});

it('includes the applied rule in the response', function (): void {
    $calculator = new DailyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 9,
            ruleType: RuleType::Daily,
        ),
        PayrollFixture::dailyRule(
            name: 'California Daily Overtime',
        ),
    );

    expect($response->appliedRule->name)->toBe('California Daily Overtime');
    expect($response->appliedRule->type)->toBe(RuleType::Daily);
    expect($response->appliedRule->threshold)->toBe(8);
});

it('rounds overtime pay to the nearest cent', function (): void {
    $calculator = new DailyOvertimeCalculator();

    $response = $calculator->calculate(
        PayrollFixture::request(
            hoursWorked: 41,
            hourlyRate: 19.99,
            ruleType: RuleType::Daily,
        ),
        PayrollFixture::dailyRule(),
    );

    expect($response->overtimePay->toDecimal())->toBe(989.50);
});
