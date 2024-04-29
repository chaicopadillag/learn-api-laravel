<?php

use App\Http\Controllers\api\v1\analytics\AnalyticsController;
use App\Http\Controllers\api\v1\auth\AuthController;
use App\Http\Controllers\api\v1\course\CourseController;
use App\Http\Controllers\api\v1\student\StudentController;
use App\Http\Controllers\api\v1\user\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

### php artisan config:publish cors

Route::prefix('v1')
    ->group(function () {

        Route::prefix('/auth')
            ->group(function () {
                Route::post('login', [AuthController::class, 'login']);
                Route::middleware('auth:sanctum')->group(function () {
                    Route::post('logout', [AuthController::class, 'logout']);
                    Route::get('user', [AuthController::class, 'authUser']);

                });

            });

        Route::middleware('auth:sanctum')->group(function () {
            Route::apiResource('users', UserController::class);
            Route::post('student/assign-courses', [StudentController::class, 'assign']);
            Route::apiResource('courses', CourseController::class);
            // dashboard
            Route::get('analytics', [AnalyticsController::class, 'index']);

        });

    });
