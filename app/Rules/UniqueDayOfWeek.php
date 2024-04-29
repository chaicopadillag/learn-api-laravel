<?php

namespace App\Rules;

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueDayOfWeek implements Rule
{
    public function passes($attribute, $value)
    {
        $daysOfWeek       = collect($value)->pluck('dayWeek');
        $uniqueDaysOfWeek = $daysOfWeek->unique();

        return $daysOfWeek->count() === $uniqueDaysOfWeek->count();
    }

    public function message()
    {
        return 'Los dias de la semana no pueden repetirse.';
    }
}
