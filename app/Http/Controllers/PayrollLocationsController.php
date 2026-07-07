<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class PayrollLocationsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        /** @var array<string, array{name: string, regions: array<string, string>}> $countries */
        $countries = config('payroll.countries', []);

        $payload = collect($countries)
            ->map(fn (array $country, string $code): array => [
                'code' => $code,
                'name' => $country['name'],
                'regions' => collect($country['regions'])
                    ->map(fn (string $name, string $regionCode): array => [
                        'code' => $regionCode,
                        'name' => $name,
                    ])
                    ->values()
                    ->all(),
            ])
            ->values()
            ->all();

        return response()->json([
            'countries' => $payload,
        ]);
    }
}
