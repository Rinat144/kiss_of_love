<?php

namespace App\Services\Game\Requests;

use App\Services\Game\DTOs\SearchActiveGameDto;
use Illuminate\Foundation\Http\FormRequest;

class SearchActiveGameRequest extends FormRequest
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
     * @return SearchActiveGameDto
     */
    final public function getDto(): SearchActiveGameDto
    {
        return new SearchActiveGameDto(
            question: $this->get('question'),
        );
    }
}
