<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['in_person', 'virtual'];

        $startDate = Carbon::now()->addDays(random_int(1, 30));
        $endDate   = Carbon::parse($startDate)->addDays(random_int(1, 30));

        return [
            'name'        => $this->faker->text(20),
            'description' => $this->faker->text(255),
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'type'        => $this->faker->randomElement($types),
        ];

    }
}
