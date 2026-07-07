<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Services;

use App\Domain\Payroll\Calculators\DailyOvertimeCalculator;
use App\Domain\Payroll\Calculators\OvertimeCalculatorInterface;
use App\Domain\Payroll\Calculators\WeeklyOvertimeCalculator;
use App\Domain\Payroll\Enums\RuleType;
use InvalidArgumentException;

final readonly class CalculatorResolver
{
    public function __construct(
        private WeeklyOvertimeCalculator $weekly,
        private DailyOvertimeCalculator $daily,
    ) {}

    /**
     * Used to resolve the incoming request to the
     * appropriate calculator based on the RuleType.
     */
    public function resolve(RuleType $type): OvertimeCalculatorInterface
    {
        return match ($type) {
            RuleType::Weekly => $this->weekly,
            RuleType::Daily => $this->daily,
            default => throw new InvalidArgumentException(
                "Unsupported rule type [{$type->value}].",
            ),
        };
    }
}
