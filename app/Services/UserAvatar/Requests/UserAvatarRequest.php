<?php

namespace App\Services\UserAvatar\Requests;

use App\Services\UserAvatar\DTOs\UserAvatarDto;
use Illuminate\Foundation\Http\FormRequest;

class UserAvatarRequest extends FormRequest
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
            'image' => [
                'required',
                'image',
                'max:2048',
                'mimes:jpg,jpeg,bmp,png',
                'dimensions:min_width=600,min_height=1067,max_width=1080,max_height=1920'
            ],
        ];
    }

    /**
     * @return UserAvatarDto
     */
    final public function getDto(): UserAvatarDto
    {
        return new UserAvatarDto(
            image: $this->file('image'),
        );
    }
}
