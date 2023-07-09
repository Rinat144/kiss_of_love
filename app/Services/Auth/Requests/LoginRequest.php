<?php

namespace App\Services\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    final public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    final public function rules(): array
    {
        return [
            'social_user_id' => ['required', 'string', 'min:8', 'max:32'],
            'api_key' => ['required', 'string', 'min:6', 'max:120'],
        ];
    }
}
