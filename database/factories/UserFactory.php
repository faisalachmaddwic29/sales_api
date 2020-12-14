<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->name,
            'password' => 'faisal',
            'email' => $this->faker->unique()->safeEmail,
            'telephone' => Str::random(13),
            'id_user_address' => null,
            'id_user_role' => null,
            'gender' => 'L',
            'status' => false,
            'photo' => '',
            'token' => Str::random(10),
        ];
    }
}
