<?php

namespace App\Http\Controllers\api\v1\analytics;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{

    public function index()
    {
        $popularCourses          = $this->popularCourses();
        $popularStudents         = $this->popularStudents();
        $totalCoursesAndStudents = $this->totalCoursesAndStudents();

        $data = [
            'popularCourses'  => $popularCourses,
            'popularStudents' => $popularStudents,
            'totals'          => $totalCoursesAndStudents,
        ];

        return response()->json($data);

    }

    private function popularCourses()
    {
        $popularCourses = Course::select('id', 'name', 'type')
            ->withCount('students')
            ->whereHas('students', function ($query) {
                $query->where('course_students.created_at', '>=', now()->subMonths(6));
            })
            ->orderByDesc('students_count')
            ->take(3)
            ->get();

        return $popularCourses;

    }

    private function popularStudents()
    {
        $popularStudents = User::withCount('courses')
            ->orderByDesc('courses_count')
            ->take(3)
            ->get();

        return $popularStudents;

    }

    private function totalCoursesAndStudents()
    {
        $totalCurses   = Course::where('status', true)->count();
        $totalStudents = User::where('role', 'student')->count();

        $data = [
            'totalCurses'   => $totalCurses,
            'totalStudents' => $totalStudents,
        ];

        return $data;

    }

}
