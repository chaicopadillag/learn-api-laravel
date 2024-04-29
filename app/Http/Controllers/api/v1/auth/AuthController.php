<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\user\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'message' => "Error de validación",
                        'errors'  => $validator->errors(),
                    ], 422);

            }

            if (Auth::attempt($request->only('email', 'password'))) {

                $user = User::select('id', 'name', 'lastname', 'email', 'password', 'role', 'status')
                    ->where('email', $request->email)
                    ->first();

                $abilities = $this->getHabilitis($user);

                $token = $user->createToken('api_token', $abilities)->plainTextToken;

                return response()->json(
                    [
                        'authUser' => new UserResource($user),
                        'token'    => $token,
                    ]);

            }

            return response()->json(
                [
                    'message' => 'Las credenciales proporcionadas son incorrectas.',
                ], 401);

        } catch (\Throwable $th) {

            Log::error('Error al inciar sesion: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al inciar sesion',
            ], 500);

        }

    }

    private function getHabilitis($user)
    {
        $abilities = [];

        switch ($user->role) {
            case 'admin':
                $abilities = ['*'];
                break;
            case 'student':
                $abilities = ['read-courses', 'assign-courses'];
                break;
            case 'teacher':
                $abilities = ['create-courses', 'update-courses', 'read-courses'];
                break;
            default:
                break;
        }

        return $abilities;

    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function authUser(Request $request)
    {

        $user = $request->user();

        return new UserResource($user);

    }

}
