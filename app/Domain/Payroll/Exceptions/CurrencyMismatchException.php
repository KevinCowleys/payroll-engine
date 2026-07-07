<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Exceptions;

use App\Domain\Payroll\Enums\Currency;
use DomainException;

final class CurrencyMismatchException extends DomainException
{
    public static function forCurrencies(
        Currency $expected,
        Currency $actual,
    ): self {
        return new self(
            sprintf(
                'Cannot operate on %s and %s values.',
                $expected->value,
                $actual->value,
            ),
        );
    }
}
