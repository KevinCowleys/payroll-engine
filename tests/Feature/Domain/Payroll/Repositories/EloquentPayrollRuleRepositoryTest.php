<?php

declare(strict_types=1);

use App\Domain\Payroll\Enums\RuleType;
use App\Domain\Payroll\Models\PayrollRule;
use App\Domain\Payroll\Repositories\EloquentPayrollRuleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns country wide rules', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create();

    $rules = $repository->findApplicableRules('US', null);

    expect($rules)->toHaveCount(1);
    expect($rules->first()->country)->toBe('US');
    expect($rules->first()->region)->toBeNull();
    expect($rules->first()->rule_type)->toBe(RuleType::Weekly);
});

it('returns both country and region specific rules', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create();

    PayrollRule::factory()
        ->daily()
        ->forCountry('US')
        ->forRegion('CA')
        ->create();

    $rules = $repository->findApplicableRules('US', 'CA');

    expect($rules)->toHaveCount(2);
    expect($rules->pluck('region')->all())
        ->toContain(null, 'CA');
    expect($rules->pluck('rule_type')->all())
        ->toContain(RuleType::Weekly, RuleType::Daily);
});

it('does not return region specific rules for another region', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create();

    PayrollRule::factory()
        ->daily()
        ->forCountry('US')
        ->forRegion('CA')
        ->create();

    PayrollRule::factory()
        ->daily()
        ->forCountry('US')
        ->forRegion('TX')
        ->create();

    $rules = $repository->findApplicableRules('US', 'CA');

    expect($rules)->toHaveCount(2);
    expect($rules->pluck('region')->all())
        ->toContain(null, 'CA')
        ->not->toContain('TX');
});

it('returns applicable rules ordered by priority', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create([
            'priority' => 100,
        ]);

    PayrollRule::factory()
        ->daily()
        ->forCountry('US')
        ->forRegion('CA')
        ->create([
            'priority' => 50,
        ]);

    PayrollRule::factory()
        ->doubleTime()
        ->forCountry('US')
        ->forRegion('CA')
        ->create([
            'priority' => 25,
        ]);

    $rules = $repository->findApplicableRules('US', 'CA');

    expect(
        $rules->map(fn (PayrollRule $rule): array => [
                $rule->region,
                $rule->priority,
            ])
            ->values()
            ->all()
    )->toBe([
        ['CA', 25],
        ['CA', 50],
        [null, 100],
    ]);
});

it('returns only currently effective rules', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create([
            'effective_from' => now()->subMonth(),
            'effective_to' => null,
        ]);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create([
            'effective_from' => now()->addDay(),
            'effective_to' => null,
        ]);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create([
            'effective_from' => now()->subMonth(),
            'effective_to' => now()->subDay(),
        ]);

    $rules = $repository->findApplicableRules('US', null);

    expect($rules)->toHaveCount(1);
    expect($rules->first()->effective_to)->toBeNull();
});

it('does not return rules from another country', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    PayrollRule::factory()
        ->weekly()
        ->forCountry('US')
        ->create();

    PayrollRule::factory()
        ->weekly()
        ->forCountry('DK')
        ->create();

    $rules = $repository->findApplicableRules('US', null);

    expect($rules)->toHaveCount(1);
    expect($rules->first()->country)->toBe('US');
});

it('returns an empty collection when no rules exist', function (): void {
    $repository = app(EloquentPayrollRuleRepository::class);

    $rules = $repository->findApplicableRules('DE', null);

    expect($rules)->toBeEmpty();
});
