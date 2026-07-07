<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Domain\Payroll\Enums\RuleType;
use Illuminate\Foundation\Http\FormRequest;

final class CalculatePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'country' => ['required', 'string', 'size:2'],
            'region' => ['nullable', 'string', 'max:10'],
            'rule_type' => ['required', 'string', 'in:weekly,daily'],
            'hours_worked' => ['required', 'numeric', 'min:0'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return RuleType
     */
    public function ruleType(): RuleType
    {
        return RuleType::from(
            $this->string('rule_type')->toString(),
        );
    }
}
