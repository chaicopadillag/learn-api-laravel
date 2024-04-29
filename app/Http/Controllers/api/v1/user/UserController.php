<?php

namespace App\Http\Controllers\api\v1\user;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\user\StoreUserRequest;
use App\Http\Requests\api\v1\user\UpdateUserRequest;
use App\Http\Resources\api\v1\user\UserCollection;
use App\Http\Resources\api\v1\user\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $page    = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $type    = $request->query('type', Role::STUDENT);

        $query = User::where('role', $type)->where('status', true)->latest('updated_at');

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return new UserCollection($users);
    }

    public function store(StoreUserRequest $request)
    {
        try {

            $user = User::create($request->all());

            return new UserResource($user);

        } catch (\Throwable $th) {

            Log::error('Error al crear usuario nuevo: ' . $th->getMessage());

            response()->json([
                'message' => 'Error al crea usuario nuevo',
            ], 500);

        }

    }

    public function show($userId)
    {
        $user = User::where('id', $userId)->where('status', true)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        }

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

        try {

            $user->update($request->all());

            return new UserResource($user);

        } catch (\Throwable $th) {

            Log::error('Error al crear usuario nuevo: ' . $th->getMessage());

            response()->json([
                'message' => 'Error al crea usuario nuevo',
            ], 500);

        }

    }

    public function destroy($userId)
    {

        $user = User::where('id', $userId)->where('status', true)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        }

        $user->status = false;
        $user->save();

        return response()->json([
            'message' => 'User deleted successfull!',
        ], 200);

    }
}
