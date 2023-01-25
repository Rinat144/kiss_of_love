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
            'question' => ['required', 'string'],
        ];
    }

    /**
     * @return CreateGameDto
     */
    final public function getDto(): CreateGameDto
    {
        return new CreateGameDto(
            question: $this->get('question'),
        );
    }
}
