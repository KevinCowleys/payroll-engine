<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Exceptions;

use Illuminate\Http\Response;
use RuntimeException;

final class PayrollRuleNotFoundException extends RuntimeException
{
    public function statusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }

    public static function forLocation(
        string $country,
        ?string $region,
    ): self {
        $location = $region === null
            ? $country
            : "{$country}/{$region}";

        return new self(
            "No payroll rule found for {$location}."
        );
    }
}
