<?php

declare(strict_types=1);

use App\Domain\Payroll\Enums\Currency;
use App\Domain\Payroll\Exceptions\CurrencyMismatchException;
use App\Domain\Payroll\ValueObjects\Money;

it('stores the amount in minor units / cents', function () {
    $money = new Money(2550, Currency::USD);

    expect($money->amount())->toBe(2550);
    expect($money->toDecimal())->toBe(25.50);
    expect($money->currency())->toBe(Currency::USD);
});

it('creates money from a decimal amount', function () {
    $money = Money::fromDecimal(25.50, Currency::USD);

    expect($money->amount())->toBe(2550);
    expect($money->toDecimal())->toBe(25.50);
    expect($money->currency())->toBe(Currency::USD);
});

it('adds two money values', function () {
    $first = Money::fromDecimal(10, Currency::USD);
    $second = Money::fromDecimal(3.20, Currency::USD);

    $result = $first->add($second);

    expect($result->toDecimal())->toBe(13.20);
});

it('can add a zero money value', function () {
    $first = Money::fromDecimal(10, Currency::USD);
    $second = Money::fromDecimal(0, Currency::USD);

    $result = $first->add($second);

    expect($result->toDecimal())->toBe(10.00);
});

it('multiplies a money value', function () {
    $money = Money::fromDecimal(10, Currency::USD);

    $result = $money->multiply(1.5);

    expect($result->toDecimal())->toBe(15.00);
});

it('returns zero when multiplied by zero', function () {
    $money = Money::fromDecimal(10, Currency::USD);

    $result = $money->multiply(0);

    expect($result->toDecimal())->toBe(0.00);
});

it('multiplies money with decimal amounts', function () {
    $money = Money::fromDecimal(10.50, Currency::USD);

    $result = $money->multiply(1.5);

    expect($result->toDecimal())->toBe(15.75);
});

it('rounds multiplication to the nearest cent', function () {
    $money = Money::fromDecimal(19.99, Currency::USD);

    $result = $money->multiply(1.5);

    expect($result->toDecimal())->toBe(29.99);
});

it('cannot add different currencies', function () {
    $usd = Money::fromDecimal(10, Currency::USD);
    $dkk = Money::fromDecimal(10, Currency::DKK);

    expect(fn () => $usd->add($dkk))->toThrow(CurrencyMismatchException::class);
});

it('does not modify the original money value', function () {
    $original = Money::fromDecimal(10, Currency::USD);

    $new = $original->multiply(2);

    expect($original->toDecimal())->toBe(10.00);
    expect($new->toDecimal())->toBe(20.00);
});
