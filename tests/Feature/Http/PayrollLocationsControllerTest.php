<?php

declare(strict_types=1);

it('returns supported payroll locations', function (): void {
    $response = $this->getJson('/api/locations');

    $response->assertOk()
        ->assertExactJson([
            'countries' => [
                [
                    'code' => 'US',
                    'name' => 'United States',
                    'regions' => [
                        [
                            'code' => 'CA',
                            'name' => 'California',
                        ],
                    ],
                ],
                [
                    'code' => 'DK',
                    'name' => 'Denmark',
                    'regions' => [],
                ],
            ],
        ]);
});
