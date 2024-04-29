<?php

namespace App\Http\Resources\api\v1\user;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"       => $this->id,
            "name"     => $this->name,
            "role"     => $this->role,
            "email"    => $this->email,
            "lastName" => $this->lastname,
            "age"      => $this->age,
            "idCard"   => $this->id_card,
            "courses"  => $this->getCourses($this->courses),
            "lessons"  => $this->getCourses($this->lessons),
        ];
    }

    private function getCourses($courses)
    {
        return $courses->map(function ($course) {
            return [
                'id'        => $course->id,
                'name'      => $course->name,
                'startDate' => $course->start_date,
                'endDate'   => $course->end_date,
                'type'      => $course->type,

            ];
        })->toArray();
    }
}
