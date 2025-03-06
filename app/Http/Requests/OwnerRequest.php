<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OwnerRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'firstname' => 'sometimes|required|string|max:200',
            'middlename' => 'sometimes|required|string|max:200',
            'lastname' => 'sometimes|required|string|max:200',
            'suffix' => 'sometimes|nullable|string|max:200',
            'email' => 'sometimes|required|string|max:50',
            'civil_status' => 'sometimes|required|string|max:20',
            'occupation' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'birth_date' => 'sometimes|required|string',
            'birth_place' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'gender' => 'sometimes|required|string|max:6',
            'contact_number' => 'sometimes|required|numeric|digits:11',
            'laboratory_id' => 'sometimes|required',
        ];
    }
}
