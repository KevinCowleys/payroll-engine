<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\Payroll\Enums\RuleType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CalculatePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<ValidationRule|string>>
     */
    public function rules(): array
    {
        /** @var array<string, array{name: string, regions: array<string, string>}> $countries */
        $countries = config('payroll.countries', []);

        return [
            'country' => ['required', 'string', 'size:2', Rule::in(array_keys($countries))],
            'region' => [
                'nullable',
                'string',
                'max:10',
                function (string $attribute, mixed $value, \Closure $fail) use ($countries): void {
                    if ($value === null || $value === '') {
                        return;
                    }

                    $country = $this->string('country')->toString();
                    $regions = $countries[$country]['regions'] ?? [];

                    if (! array_key_exists((string) $value, $regions)) {
                        $fail('The selected region is invalid for the chosen country.');
                    }
                },
            ],
            'rule_type' => ['required', 'string', 'in:weekly,daily'],
            'hours_worked' => ['required', 'numeric', 'min:0'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function ruleType(): RuleType
    {
        return RuleType::from(
            $this->string('rule_type')->toString(),
        );
    }
}
