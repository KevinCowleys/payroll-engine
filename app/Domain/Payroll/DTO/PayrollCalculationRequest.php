<?php

declare(strict_types=1);

namespace App\Domain\Payroll\DTOs;

use App\Domain\Payroll\ValueObjects\Money;

final readonly class PayrollCalculationRequest
{
    public function __construct(
        public string $country,
        public ?string $region,
        public Money $hoursWorked,
        public float $hourlyRate,
    ) {
    }
}
