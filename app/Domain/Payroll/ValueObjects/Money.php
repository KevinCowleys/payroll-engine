<?php

declare(strict_types=1);

namespace App\Domain\Payroll\ValueObjects;

use App\Domain\Payroll\Enums\Currency;
use App\Domain\Payroll\Exceptions\CurrencyMismatchException;

final readonly class Money
{
    public function __construct(
        private int $amount,
        private Currency $currency,
    ) {}

    public static function fromDecimal(float $amount, Currency $currency): self
    {
        return new self(
            amount: (int) round($amount * 100),
            currency: $currency,
        );
    }

    public function add(self $money): self
    {
        $this->assertSameCurrency($money);

        return new self(
            amount: $this->amount + $money->amount,
            currency: $this->currency,
        );
    }

    public function multiply(float $multiplier): self
    {
        return new self(
            amount: (int) round($this->amount * $multiplier),
            currency: $this->currency,
        );
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function toDecimal(): float
    {
        return $this->amount / 100;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    private function assertSameCurrency(self $money): void
    {
        if ($this->currency !== $money->currency()) {
            throw CurrencyMismatchException::forCurrencies(
                $this->currency,
                $money->currency(),
            );
        }
    }
}
