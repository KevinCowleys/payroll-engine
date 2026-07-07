<?php

declare(strict_types=1);

namespace App\Domain\Payroll\DTOs;

use App\Domain\Payroll\Enums\RuleType;

final readonly class PayrollCalculationRequest
{
    public function __construct(
        public string $country,
        public ?string $region,
        public RuleType $ruleType,
        public float $hoursWorked,
        public float $hourlyRate,
    ) {}
}
