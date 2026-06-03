<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->vendor(),
            'company_name' => fake()->company(),
            'commission_type' => 'percentage',
            'commission_value' => 10,
            'cookie_duration_days' => 30,
        ];
    }

    public function flatCommission(float|int $commissionValue = 15000): static
    {
        return $this->state(fn () => [
            'commission_type' => 'flat',
            'commission_value' => $commissionValue,
        ]);
    }

    public function percentageCommission(float|int $commissionValue = 10): static
    {
        return $this->state(fn () => [
            'commission_type' => 'percentage',
            'commission_value' => $commissionValue,
        ]);
    }
}
