<?php

namespace App\Http\Requests\Auth;

use App\Services\Auth\DTOs\RegisterDTO;
use App\Services\Auth\Enum\GenderSelectionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:15'],
            'login' => ['required', 'min:8', 'max:32'],
            'date_of_birth' => ['required', 'date_format:d-m-Y', 'before:today'],
            'city_id' => ['required', 'integer', 'exists:App\Models\City,id'],
            'balance' => ['integer'],
            'gender' => ['required', Rule::in(array_column(GenderSelectionEnum::cases(), 'value'))],
            'password' => ['required', 'string', 'confirmed', 'min:6', 'max:120'],
        ];
    }

    /**
     * @return RegisterDTO
     */
    public function getDto(): RegisterDTO
    {
        return new RegisterDTO(
            name: $this->get('name'),
            date_of_birth: $this->get('date_of_birth'),
            gender: $this->get('gender'),
            login: $this->get('login'),
            password: $this->get('password'),
            city_id: $this->get('city_id')
        );
    }
}
