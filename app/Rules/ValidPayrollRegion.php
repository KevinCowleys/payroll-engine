<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class ValidPayrollRegion implements ValidationRule
{
    /**
     * @param  array<string, array{name: string, regions: array<string, string>}>  $countries
     */
    public function __construct(
        private readonly array $countries,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $country = request()->string('country')->toString();
        $regions = $this->countries[$country]['regions'] ?? [];

        if (! array_key_exists((string) $value, $regions)) {
            $fail('The selected region is invalid for the chosen country.');
        }
    }
}
