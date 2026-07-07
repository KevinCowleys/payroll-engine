<?php

namespace App\Domain\Payroll\Exceptions;

use RuntimeException;

final class PayrollRuleNotFoundException extends RuntimeException
{
    /**
     * @param string $country
     * @param ?string $region
     *
     * @return self
     */
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
