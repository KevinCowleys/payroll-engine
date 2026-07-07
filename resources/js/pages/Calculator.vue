<script setup lang="ts">
import { payrollApi } from '@/lib/payroll-api';
import type {
    PayrollCalculationResult,
    PayrollCountry,
    PayrollLocationsResponse,
    RuleType,
} from '@/types';
import { Head, useHttp } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

const locationsHttp = useHttp({});
const locations = ref<PayrollCountry[]>([]);

const form = useHttp({
    country: '',
    region: '' as string,
    rule_type: 'weekly' as RuleType,
    hours_worked: 40,
    hourly_rate: 20,
});

const result = ref<PayrollCalculationResult | null>(null);
const notFoundMessage = ref<string | null>(null);

const selectedCountry = computed(() =>
    locations.value.find((country) => country.code === form.country),
);

const availableRegions = computed(
    () => selectedCountry.value?.regions ?? [],
);

const hasRegions = computed(() => availableRegions.value.length > 0);

const currencyFormatter = computed(() => {
    const currency = result.value?.currency ?? 'USD';

    return new Intl.NumberFormat(undefined, {
        style: 'currency',
        currency,
    });
});

function formatCurrency(amount: number): string {
    return currencyFormatter.value.format(amount);
}

function loadLocations(): void {
    locationsHttp.get(payrollApi.locations, {
        onSuccess: (response) => {
            const data = response as PayrollLocationsResponse;
            locations.value = data.countries;

            if (locations.value.length === 0) {
                return;
            }

            form.country = locations.value[0].code;
            form.region = '';
        },
    });
}

function calculate(): void {
    result.value = null;
    notFoundMessage.value = null;

    form.post(payrollApi.calculate, {
        onSuccess: (response) => {
            result.value = response as PayrollCalculationResult;
        },
        onError: (errors) => {
            const errorBag = errors as Record<string, string>;

            if (errorBag.message) {
                notFoundMessage.value = errorBag.message;
            }
        },
    });
}

watch(
    () => form.country,
    () => {
        form.region = '';
        notFoundMessage.value = null;
    },
);

onMounted(() => {
    loadLocations();
});
</script>

<template>
    <Head title="Payroll Calculator" />

    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
        <main class="w-full max-w-2xl">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-medium">Payroll Calculator</h1>
                <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Calculate regular and overtime pay based on location-specific
                    rules.
                </p>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
                <form class="space-y-5" @submit.prevent="calculate">
                    <div
                        v-if="notFoundMessage"
                        class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-300"
                    >
                        {{ notFoundMessage }}
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="country" class="mb-1 block text-sm font-medium">
                                Country
                            </label>
                            <select
                                id="country"
                                v-model="form.country"
                                :disabled="
                                    locationsHttp.processing ||
                                    locations.length === 0
                                "
                                class="w-full rounded-md border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#0a0a0a] disabled:opacity-50"
                            >
                                <option
                                    v-for="country in locations"
                                    :key="country.code"
                                    :value="country.code"
                                >
                                    {{ country.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.country" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.country }}
                            </p>
                        </div>

                        <div v-if="hasRegions">
                            <label for="region" class="mb-1 block text-sm font-medium">
                                Region
                            </label>
                            <select
                                id="region"
                                v-model="form.region"
                                :disabled="locationsHttp.processing"
                                class="w-full rounded-md border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#0a0a0a] disabled:opacity-50"
                            >
                                <option value="">None</option>
                                <option
                                    v-for="region in availableRegions"
                                    :key="region.code"
                                    :value="region.code"
                                >
                                    {{ region.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.region" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.region }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label for="rule_type" class="mb-1 block text-sm font-medium">
                            Rule type
                        </label>
                        <select
                            id="rule_type"
                            v-model="form.rule_type"
                            class="w-full rounded-md border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#0a0a0a]"
                        >
                            <option value="weekly">Weekly</option>
                            <option value="daily">Daily</option>
                        </select>
                        <p v-if="form.errors.rule_type" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.rule_type }}
                        </p>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="hours_worked" class="mb-1 block text-sm font-medium">
                                Hours worked
                            </label>
                            <input
                                id="hours_worked"
                                v-model.number="form.hours_worked"
                                type="number"
                                min="0"
                                step="0.5"
                                class="w-full rounded-md border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#0a0a0a]"
                            />
                            <p v-if="form.errors.hours_worked" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.hours_worked }}
                            </p>
                        </div>

                        <div>
                            <label for="hourly_rate" class="mb-1 block text-sm font-medium">
                                Hourly rate
                            </label>
                            <input
                                id="hourly_rate"
                                v-model.number="form.hourly_rate"
                                type="number"
                                min="0"
                                step="0.01"
                                class="w-full rounded-md border border-[#e3e3e0] bg-white px-3 py-2 text-sm dark:border-[#3E3E3A] dark:bg-[#0a0a0a]"
                            />
                            <p v-if="form.errors.hourly_rate" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.hourly_rate }}
                            </p>
                        </div>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing || locationsHttp.processing"
                        class="w-full rounded-md bg-[#1b1b18] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#333] disabled:cursor-not-allowed disabled:opacity-50 dark:bg-[#EDEDEC] dark:text-[#1b1b18] dark:hover:bg-white"
                    >
                        {{
                            form.processing
                                ? 'Calculating…'
                                : 'Calculate payroll'
                        }}
                    </button>
                </form>
            </div>

            <div class="mt-6 rounded-lg bg-white p-6 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
                <h2 class="mb-4 text-lg font-medium">Results</h2>

                <div v-if="form.processing" class="space-y-3" aria-live="polite">
                    <div class="h-4 w-3/4 animate-pulse rounded bg-[#e3e3e0] dark:bg-[#3E3E3A]" />
                    <div class="h-4 w-1/2 animate-pulse rounded bg-[#e3e3e0] dark:bg-[#3E3E3A]" />
                    <div class="h-4 w-2/3 animate-pulse rounded bg-[#e3e3e0] dark:bg-[#3E3E3A]" />
                </div>

                <div v-else-if="result" class="space-y-4">
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                Regular hours
                            </dt>
                            <dd class="text-lg font-medium">
                                {{ result.regular_hours }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                Overtime hours
                            </dt>
                            <dd class="text-lg font-medium">
                                {{ result.overtime_hours }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                Regular pay
                            </dt>
                            <dd class="text-lg font-medium">
                                {{ formatCurrency(result.regular_pay) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                                Overtime pay
                            </dt>
                            <dd class="text-lg font-medium">
                                {{ formatCurrency(result.overtime_pay) }}
                            </dd>
                        </div>
                    </dl>

                    <div class="border-t border-[#e3e3e0] pt-4 dark:border-[#3E3E3A]">
                        <dt class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Gross pay
                        </dt>
                        <dd class="text-2xl font-medium">
                            {{ formatCurrency(result.gross_pay) }}
                        </dd>
                    </div>

                    <div class="rounded-md bg-[#FDFDFC] px-4 py-3 text-sm dark:bg-[#0a0a0a]">
                        <p class="font-medium">{{ result.rule.name }}</p>
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">
                            {{ result.rule.type }} rule - threshold
                            {{ result.rule.threshold }} hours
                        </p>
                    </div>
                </div>

                <p v-else class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Enter your details and click calculate to see results.
                </p>
            </div>
        </main>
    </div>
</template>
