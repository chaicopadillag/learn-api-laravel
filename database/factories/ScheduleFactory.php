<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dayOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday' /*'saturday', 'sunday'*/];

        $startTime = Carbon::now()->startOfDay()->addHours(8)->addMinutes(random_int(0, 59));
        $endTime   = Carbon::now()->startOfDay()->addHours(17)->addMinutes(random_int(0, 59));

        return [
            'day_week'   => $this->faker->randomElement($dayOfWeek),
            'start_time' => $startTime,
            'end_time'   => $endTime,
        ];

    }
}
