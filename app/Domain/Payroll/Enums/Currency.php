<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Enums;

enum Currency: string
{
    case USD = 'USD';
    case DKK = 'DKK';
    case EUR = 'EUR';
}
