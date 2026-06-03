<?php

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Affiliate>
 */
class AffiliateFactory extends Factory
{
    protected $model = Affiliate::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->affiliate(),
            'referral_code' => 'aff_' . Str::lower(Str::random(8)),
            'bank_name' => fake()->randomElement(['BCA', 'BRI', 'Mandiri']),
            'bank_account_number' => fake()->numerify('############'),
            'bank_account_name' => fake()->name(),
        ];
    }
}
