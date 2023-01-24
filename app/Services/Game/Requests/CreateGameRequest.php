<?php

namespace App\Services\Game\Requests;

use App\Services\Game\DTOs\CreateGameDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateGameRequest extends FormRequest
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
            'question' => ['string'],
        ];
    }

    /**
     * @return CreateGameDto
     */
    public function getDto(): CreateGameDto
    {
        return new CreateGameDto(
            question: $this->get('question'),
        );
    }
}
