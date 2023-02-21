<?php

namespace App\Services\Chat\Requests;

use App\Services\Chat\DTOs\SendMessageDto;
use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
            'chat_id' => ['required', 'integer'],
            'message' => ['required', 'string', 'max:120'],
        ];
    }

    final public function getDto(): SendMessageDto
    {
        return new SendMessageDto(
            chatId: $this->get('chat_id'),
            message: $this->get('message')
        );
    }
}
