<?php

namespace App\Http\Controllers\api\v1\course;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\course\CourseRequest;
use App\Http\Resources\api\v1\course\CourseResource;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page    = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);

        $query = Course::where('status', true)
            ->latest('updated_at')
            ->with(['teacher:id,name,lastname']);

        $courses = $query->paginate($perPage, ['*'], 'page', $page);

        return CourseResource::collection($courses);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        try {

            $course = Course::create($request->all());

            $schedulesData = $request->input('schedules', []);

            $schedules = [];
            foreach ($schedulesData as $scheduleData) {
                $schedules[] = new Schedule([
                    'day_week'   => $scheduleData['day_week'],
                    'start_time' => $scheduleData['start_time'],
                    'end_time'   => $scheduleData['end_time'],
                ]);
            }

            $course->schedules()->saveMany($schedules);
            $course->load('teacher:id,name,lastname');

            return new CourseResource($course);

        } catch (\Throwable $th) {

            Log::error('Error al crear curso nuevo: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al crea curso nuevo',
            ], 500);

        }

    }

    public function show($courseId)
    {
        $user = Course::where('id', $courseId)
            ->where('status', true)
            ->with(['teacher:id,name,lastname'])
            ->with('schedules')
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        }

        return new CourseResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, Course $course)
    {
        try {
            $course->update($request->all());

            $schedulesData = $request->input('schedules', []);

            $schedules = [];

            foreach ($schedulesData as $scheduleData) {
                $schedules[] = new Schedule([
                    'day_week'   => $scheduleData['day_week'],
                    'start_time' => $scheduleData['start_time'],
                    'end_time'   => $scheduleData['end_time'],
                ]);
            }

            $course->schedules()->delete();
            $course->schedules()->saveMany($schedules);

            $course->load('teacher:id,name,lastname');

            return new CourseResource($course);

        } catch (\Throwable $th) {
            Log::error('Error al actualizar el curso: ' . $th->getMessage());
            return response()->json([
                'message' => 'Error al actualizar el curso',
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($courseId)
    {
        $user = Course::where('id', $courseId)->where('status', true)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        }

        $user->status = false;
        $user->save();

        return response()->json([
            'message' => 'Course deleted successfull!',
        ], 200);

    }
}
