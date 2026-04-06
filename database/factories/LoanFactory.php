<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Loan;
use App\Models\User;
use App\Models\Item;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'status' => 'pending',
            'expected_return_date' => $this->faker->dateTimeBetween('now', '+30 days'),
        ];
    }
}
