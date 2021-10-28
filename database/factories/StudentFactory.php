<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->name(),
            'current_school' => $this->faker->name(),
            'previous_school' => $this->faker->name(),
            'parents_details' => $this->faker->name(),
            'is_approved' => $this->faker->name(),
            'deleted_at' => $this->faker->name(),	
        ];
    }
}
