<?php

namespace App\Http\Requests\api\v1\user;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    private $regexEmail = "regex:/^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*()])[a-zA-Z0-9!@#$%^&*()]+$/";
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && (
            $user->tokenCan("create-user") ||
            $user->tokenCan("update-user")
        );

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {

        $role = $request->input('role');

        $rules = [
            'role'                 => ['required', 'string', Rule::in([Role::ADMIN, Role::STUDENT, Role::TEACHER])],
            'name'                 => ['required', 'string', 'min:3', 'max:100'],
            'lastName'             => ['required', 'string', 'min:3', 'max:100'],
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'             => ['required', 'string', 'min:8', $this->regexEmail],
            'passwordConfirmation' => ['required', 'same:password'],
            'idCard'               => ['nullable', 'string', 'digits:11', Rule::unique('users', 'id_card')],

        ];

        if ($role === Role::STUDENT) {
            $rules['lastName'] = ['nullable', 'string', 'max:100'];
            $rules['age']      = ['required', 'integer', 'min:18'];
            $rules['idCard']   = ['required', 'string', 'digits:11', Rule::unique('users', 'id_card')];
        }

        return $rules;

    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id_card'  => $this->idCard,
            'lastname' => $this->lastName,
        ]);
    }

    public function messages()
    {
        return [
            'password.regex' => 'La contraseña debe tener 8 caracteres, un número, una mayúscula, un carácter especial',

        ];
    }
}
