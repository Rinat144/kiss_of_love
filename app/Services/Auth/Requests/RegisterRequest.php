<?php

namespace App\Services\Auth\Requests;

use App\Models\City;
use App\Services\Auth\DTOs\RegisterDto;
use App\Services\Auth\Enum\GenderSelectionEnum;
use App\Services\Auth\Enum\SocialTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:15'],
            'social_user_id' => ['required', 'min:8', 'max:32'],  //social_user_id
            'social_type' => ['required', Rule::in(array_column(SocialTypeEnum::cases(), 'value'))],
            'date_of_birth' => ['required', 'date_format:Y-m-d', 'before:today'],
            'city_id' => ['required', 'integer', 'exists:' . City::class . ',id'],
            'gender' => ['required', Rule::in(array_column(GenderSelectionEnum::cases(), 'value'))],
            'api_key' => ['required', 'string', 'confirmed', 'min:6', 'max:120'],  //api_key
        ];
    }

    /**
     * @return RegisterDto
     */
    final public function getDto(): RegisterDto
    {
        return new RegisterDto(
            name: $this->get('name'),
            date_of_birth: $this->get('date_of_birth'),
            gender: $this->get('gender'),
            login: $this->get('social_user_id'),
            socialTypeEnum: $this->get('social_type'),
            password: $this->get('api_key'),
            city_id: $this->get('city_id')
        );
    }
}
