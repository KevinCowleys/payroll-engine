<?php

declare(strict_types=1);

namespace App\Domain\Payroll\Models;

use App\Domain\Payroll\Enums\Currency;
use App\Domain\Payroll\Enums\RuleType;
use Database\Factories\PayrollRuleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $country
 * @property ?string $region
 * @property string $name
 * @property RuleType $rule_type
 * @property int $threshold
 * @property string $overtime_multiplier
 * @property Currency $currency
 * @property int $priority
 * @property \Illuminate\Support\Carbon $effective_from
 * @property ?\Illuminate\Support\Carbon $effective_to
 */
class PayrollRule extends Model
{
    /** @use HasFactory<PayrollRuleFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'country',
        'region',
        'rule_type',
        'name',
        'threshold',
        'overtime_multiplier',
        'currency',
        'priority',
        'effective_from',
        'effective_to',
    ];

    /**
     * @return array
     */
    protected function casts(): array
    {
        return [
            'country' => 'string',
            'region' => 'string',
            'rule_type' => RuleType::class,
            'name' => 'string',
            'threshold' => 'integer',
            'overtime_multiplier' => 'decimal:2',
            'currency' => Currency::class,
            'priority' => 'integer',
            'effective_from' => 'date',
            'effective_to' => 'date',
        ];
    }
}
