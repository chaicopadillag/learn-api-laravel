<?php

namespace App\Http\Requests\api\v1\user;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {

        $method = $this->method();

        $userId = $this->route('user');
        $role   = $request->input('role');
        $rules  = [];

        if ($method === 'PUT') {

            $rules = [
                'role'                 => ['required', 'string', Rule::in([Role::ADMIN, Role::STUDENT, Role::TEACHER])],
                'name'                 => ['required', 'string', 'min:3', 'max:100'],
                'lastName'             => ['required', 'string', 'min:3', 'max:100'],
                'email'                => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
                'password'             => ['required', 'string', 'min:8', $this->regexEmail],
                'passwordConfirmation' => ['required', 'same:password'],
                'idCard'               => ['nullable', 'string', 'digits:11', Rule::unique('users', 'id_card')->ignore($userId)],
            ];

            if ($role === Role::STUDENT) {
                $rules['lastName'] = ['string', 'max:100'];
                $rules['age']      = ['required', 'integer', 'min:18'];
                $rules['idCard']   = ['required', 'string', 'digits:11', Rule::unique('users', 'id_card')->ignore($userId)];
            }

        } else {
            $rules = [
                'role'                 => ['sometimes', 'string', Rule::in([Role::ADMIN, Role::STUDENT, Role::TEACHER])],
                'name'                 => ['sometimes', 'string', 'min:3', 'max:100'],
                'lastName'             => ['sometimes', 'string', 'min:3', 'max:100'],
                'email'                => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
                'password'             => ['sometimes', 'string', 'min:8', $this->regexEmail],
                'passwordConfirmation' => ['sometimes', 'same:password'],
                'lastName'             => ['string', 'max:100'],
                'age'                  => ['sometimes', 'integer', 'min:18'],
                'idCard'               => ['sometimes', 'string', 'digits:11', Rule::unique('users', 'id_card')->ignore($userId)],
            ];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->idCard) {
            $this->merge(['id_card' => $this->idCard]);
        }

        if ($this->lastName) {
            $this->merge(['lastname' => $this->lastName]);
        }
    }

    public function messages()
    {
        return [
            'password.regex' => 'La contraseña debe tener 8 caracteres, un número, una mayúscula, un carácter especial',
        ];
    }
}
