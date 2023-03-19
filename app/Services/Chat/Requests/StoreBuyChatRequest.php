<?php

namespace App\Services\Chat\Requests;

use App\Services\Chat\DTOs\StoreBuyChatDto;
use Illuminate\Foundation\Http\FormRequest;

class StoreBuyChatRequest extends FormRequest
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
            'selected_user_id' => ['required', 'integer'],
            'game_id' => ['required', 'integer'],
        ];
    }

    /**
     * @return StoreBuyChatDto
     */
    final public function getDto(): StoreBuyChatDto
    {
        return new StoreBuyChatDto(
            selected_user_id: $this->get('selected_user_id'),
            game_id: $this->get('game_id'),
        );
    }
}
