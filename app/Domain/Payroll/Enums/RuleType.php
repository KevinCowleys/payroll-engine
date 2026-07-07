<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Enums;

enum RuleType: string
{
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
}
