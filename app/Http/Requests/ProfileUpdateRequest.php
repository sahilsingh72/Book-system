<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Change to true if you want authorization to be handled elsewhere
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins')->ignore(Auth::id()), // Ignore the current user's email for uniqueness check
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('admins')->ignore(Auth::id()), // Ignore the current user's username for uniqueness check
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}
