<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = User::factory(10)->create(['role' => 'teacher']);

        foreach ($teachers as $teacher) {

            $courses = Course::factory(5)->create([
                'teacher_id' => $teacher->id,
            ]);

            foreach ($courses as $course) {
                Schedule::factory(3)->create([
                    'course_id' => $course->id,
                ]);
            }

            // $this->command->info(json_encode($courses));
            $students = User::factory(5)->create(['role' => 'student']);

            $students->each(function ($student) use ($courses) {
                $student->courses()->attach($courses->pluck('id'));
            });

        }

    }
}
