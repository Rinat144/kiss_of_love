<?php

namespace App\Services\Game\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectLikeUserRequest extends FormRequest
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
            'select_user_id' => ['required', 'integer']
        ];
    }
}
