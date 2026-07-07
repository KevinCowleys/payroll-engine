<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\Payroll\Enums\RuleType;
use App\Rules\ValidPayrollRegion;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class CalculatePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, ValidationRule|string>>
     */
    public function rules(): array
    {
        /** @var array<string, array{name: string, regions: array<string, string>}> $countries */
        $countries = config('payroll.countries', []);

        return [
            'country' => ['required', 'string', 'size:2', 'in:'.implode(',', array_keys($countries))],
            'region' => ['nullable', 'string', 'max:10', new ValidPayrollRegion($countries)],
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
