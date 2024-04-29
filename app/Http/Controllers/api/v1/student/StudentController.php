<?php

namespace App\Http\Controllers\api\v1\student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function assign(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId'    => ['required', 'uuid'],
                'courses'   => ['required', 'array'],
                'courses.*' => ['required', 'uuid'],
            ]);

            if ($validator->fails()) {

                $data = [
                    "message" => "Error de validación",
                    'errors'  => $validator->errors(),
                ];

                return response()->json($data, 422);
            }

            $student = User::find($request->input('userId'));

            $student->courses()->sync($request->input('courses'));
            $student->load('courses');

            return $student;

        } catch (\Throwable $th) {
            Log::error('Error al asignar cursos al estudiante: ' . $th->getMessage());
            return response()->json([
                'message' => 'Error al asignar cursos al estudiante',
            ], 500);

        }
    }

}
