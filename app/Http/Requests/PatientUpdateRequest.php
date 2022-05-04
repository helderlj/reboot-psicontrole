<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'max:255', 'string'],
            'phone_alt' => ['nullable', 'max:255', 'string'],
            'birthday' => ['required', 'date'],
            'starting_date' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
            'summary' => ['nullable', 'max:255', 'string'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
