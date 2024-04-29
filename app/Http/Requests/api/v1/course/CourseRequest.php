<?php

namespace App\Http\Requests\api\v1\course;

use App\Enums\DayOfWeek;
use App\Enums\TypeCourse;
use App\Rules\UniqueDayOfWeek;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && (
            $user->tokenCan("create-courses") ||
            $user->tokenCan("update-courses")
        );

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'min:3', 'max:50', Rule::unique('courses')->ignore($this->route('course')->id ?? null)],
            'description'           => ['nullable', 'max:255'],
            'startDate'             => ['required', 'date', 'date_format:Y-m-d'],
            'endDate'               => ['required', 'date', 'date_format:Y-m-d', 'after:startDate
', ],
            'type'                  => ['required', 'string', Rule::in([TypeCourse::IN_PERSON, TypeCourse::VIRTUAL])],
            'teacherId'             => ['required', 'uuid'],

            'schedules'             => ['required', 'array', 'min:1', new UniqueDayOfWeek],
            'schedules.*'           => ['required', 'array'],
            'schedules.*.dayWeek'   => ['required', 'string', Rule::in([DayOfWeek::MONDAY, DayOfWeek::TUESDAY, DayOfWeek::WEDNESDAY, DayOfWeek::THURSDAY, DayOfWeek::FRIDAY, DayOfWeek::SATURDAY, DayOfWeek::SUNDAY])],
            'schedules.*.startTime' => ['required', 'date_format:H:i'],
            'schedules.*.endTime'   => ['required', 'date_format:H:i', 'after:schedules.*.startTime'],

        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'start_date' => $this->startDate,
            'end_date'   => $this->endDate,
            'teacher_id' => $this->teacherId,
        ]);

    }

    protected function passedValidation()
    {
        $schedules = [];

        foreach ($this->input('schedules') as $schedule) {
            $schedules[] = [
                'day_week'   => isset($schedule['dayWeek']) ? $schedule['dayWeek'] : null,
                'start_time' => isset($schedule['startTime']) ? $schedule['startTime'] : null,
                'end_time'   => isset($schedule['endTime']) ? $schedule['endTime'] : null,
            ];
        }

        $this->merge(['schedules' => $schedules]);
    }

}
