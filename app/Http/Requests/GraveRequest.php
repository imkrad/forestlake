<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GraveRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:200',
            'birth_date' => 'sometimes|required|string',
            'death_date' => 'sometimes|required|string',
            'burial_date' => 'sometimes|required|string',
            'cause_of_death' => 'sometimes|required|string',
            'owner' => 'sometimes|required|string',
            'lot_id' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|string',
        ];
    }
}
