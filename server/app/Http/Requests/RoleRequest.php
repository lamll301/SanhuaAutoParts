<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'name' => [
                'required', 
                'string', 
                'max:100', 
                $isCreate ? 'unique:roles,name' : 'unique:roles,name,' . $id
            ]
        ];
    }
}
