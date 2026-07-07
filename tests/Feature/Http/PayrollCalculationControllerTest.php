<?php

declare(strict_types=1);

use Database\Seeders\PayrollRuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(PayrollRuleSeeder::class);
});

it('calculates weekly payroll', function (): void {
    $response = $this->postJson('/api/payroll/calculate', [
        'country' => 'US',
        'region' => null,
        'rule_type' => 'weekly',
        'hours_worked' => 45,
        'hourly_rate' => 20,
    ]);

    $response->assertOk()
        ->assertExactJson([
            'regular_hours' => 40,
            'overtime_hours' => 5,
            'regular_pay' => 800.0,
            'overtime_pay' => 150.0,
            'gross_pay' => 950.0,
            'currency' => 'USD',
            'rule' => [
                'name' => 'US Federal Weekly Overtime',
                'type' => 'weekly',
                'threshold' => 40,
            ],
        ]);
});

it('calculates daily payroll', function (): void {
    $response = $this->postJson('/api/payroll/calculate', [
        'country' => 'US',
        'region' => 'CA',
        'rule_type' => 'daily',
        'hours_worked' => 10,
        'hourly_rate' => 20,
    ]);

    $response->assertOk()
        ->assertExactJson([
            'regular_hours' => 8,
            'overtime_hours' => 2,
            'regular_pay' => 160.0,
            'overtime_pay' => 60.0,
            'gross_pay' => 220.0,
            'currency' => 'USD',
            'rule' => [
                'name' => 'California Daily Overtime',
                'type' => 'daily',
                'threshold' => 8,
            ],
        ]);
});

it('validates the request', function (): void {
    $response = $this->postJson('/api/payroll/calculate', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'country',
            'rule_type',
            'hours_worked',
            'hourly_rate',
        ]);
});

it('validates the rule type', function (): void {
    $response = $this->postJson('/api/payroll/calculate', [
        'country' => 'US',
        'rule_type' => 'monthly',
        'hours_worked' => 40,
        'hourly_rate' => 20,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'rule_type',
        ]);
});

it('returns not found when no payroll rule exists', function (): void {
    $response = $this->postJson('/api/payroll/calculate', [
        'country' => 'FR',
        'region' => null,
        'rule_type' => 'weekly',
        'hours_worked' => 40,
        'hourly_rate' => 20,
    ]);

    $response->assertNotFound()
        ->assertExactJson([
            'message' => 'No payroll rule found for FR.',
        ]);
});
