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
        return [
            'email' => 'sometimes|required|email|max:150|unique:users,email,'.$this->id,
            'name' => 'sometimes|required|string|max:200',
            'middlename' => 'sometimes|required|string|max:50',
            'suffix' => 'sometimes|nullable|string|max:10',
            'gender' => 'sometimes|required|string|max:6',
            'mobile' => 'sometimes|required|numeric|digits:11|unique:user_profiles,mobile,'.$this->profile_id,
            'laboratory_id' => 'sometimes|required',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
