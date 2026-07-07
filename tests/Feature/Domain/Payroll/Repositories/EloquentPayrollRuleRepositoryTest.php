<?php

declare(strict_types=1);

use App\Domain\Payroll\Enums\RuleType;
use App\Domain\Payroll\Models\PayrollRule;
use App\Domain\Payroll\Repositories\EloquentPayrollRuleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns the matching weekly rule', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create();

    $rule = $repository->findApplicableRule(
        'US',
        null,
        RuleType::Weekly,
    );

    expect($rule)->not->toBeNull();
    expect($rule->rule_type)->toBe(RuleType::Weekly);
});

it('returns the matching daily rule', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    PayrollRule::factory()
        ->daily()
        ->forCountry('US')
        ->forRegion('CA')
        ->create();

    $rule = $repository->findApplicableRule(
        'US',
        'CA',
        RuleType::Daily,
    );

    expect($rule)->not->toBeNull();
    expect($rule->rule_type)->toBe(RuleType::Daily);
});

it('prefers the region specific rule', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create([
            'priority' => 100,
        ]);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->forRegion('CA')
        ->create([
            'priority' => 50,
        ]);

    $rule = $repository->findApplicableRule(
        'US',
        'CA',
        RuleType::Weekly,
    );

    expect($rule)->not->toBeNull();
    expect($rule->region)->toBe('CA');
    expect($rule->priority)->toBe(50);
});

it('returns null when no matching rule exists', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    $rule = $repository->findApplicableRule(
        'DE',
        null,
        RuleType::Weekly,
    );

    expect($rule)->toBeNull();
});

it('ignores rules that are not currently effective', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create([
            'effective_from' => now()->addDay(),
        ]);

    $rule = $repository->findApplicableRule(
        'US',
        null,
        RuleType::Weekly,
    );

    expect($rule)->toBeNull();
});
