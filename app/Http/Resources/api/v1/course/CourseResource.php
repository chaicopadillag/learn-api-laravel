<?php

namespace App\Http\Resources\api\v1\course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'startDate'   => $this->start_date,
            'endDate'     => $this->end_date,
            'type'        => $this->type,
            'teacher'     => $this->teacher,
            'schedules'   => $this->schedules,
        ];
    }
}
