export type RuleType = 'weekly' | 'daily';

export type PayrollRegion = {
    code: string;
    name: string;
};

export type PayrollCountry = {
    code: string;
    name: string;
    regions: PayrollRegion[];
};

export type PayrollLocationsResponse = {
    countries: PayrollCountry[];
};

export type PayrollCalculationInput = {
    country: string;
    region: string | null;
    rule_type: RuleType;
    hours_worked: number;
    hourly_rate: number;
};

export type AppliedRule = {
    name: string;
    type: RuleType;
    threshold: number;
};

export type PayrollCalculationResult = {
    regular_hours: number;
    overtime_hours: number;
    regular_pay: number;
    overtime_pay: number;
    gross_pay: number;
    currency: string;
    rule: AppliedRule;
};
