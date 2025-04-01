<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('POST');
        if (!$isCreate) {
            $id = $this->route('id');
        }

        return [
            'username' => [
                'string',
                'max:255',
                $isCreate ? 'required' : 'sometimes',
                $isCreate ? 'unique:users,username' : 'unique:users,username,' . $id
            ],
            'password' => [
                'string',
                'min:6',
                'max:255',
                $isCreate ? 'required' : 'sometimes',
            ],
            'email' => [
                'nullable',
                'email',
                $isCreate ? 'unique:users,email' : 'unique:users,email,' . $id
            ],
            'phone' => [
                'nullable', 'string', 'regex:/^[0-9]{9,15}$/'
            ],
            'name' => [
                'nullable', 'string', 'max:100'
            ],
            'address' => [
                'nullable', 'string', 'max:255'
            ],
            'date_of_birth' => [
                'nullable', 'date', 'before:today'
            ],
            'status' => [
                'sometimes', 'integer', 'in:0,1,2'
            ],
            'role_id' => [
                'nullable', 'integer', 'exists:roles,id'
            ]
        ];
    }
}
