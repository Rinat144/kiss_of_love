<?php

namespace App\Services\Game\Requests;

use App\Services\Game\DTOs\AddAnswerTheQuestionsDto;
use Illuminate\Foundation\Http\FormRequest;

class AddAnswerTheQuestionsRequest extends FormRequest
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
            'answer_to_first_user' => ['string', 'nullable'],
            'answer_to_second_user' => ['string', 'nullable'],
            'answer_to_third_user' => ['string', 'nullable'],
            'answer_to_fourth_user' => ['string', 'nullable'],
            'answer_to_fifth_user' => ['string', 'nullable'],
            'answer_to_sixth_user' => ['string', 'nullable'],
        ];
    }

    /**
     * @return AddAnswerTheQuestionsDto
     */
    final public function getDto(): AddAnswerTheQuestionsDto
    {
        return new AddAnswerTheQuestionsDto(
            answer_to_first_user: $this->get('answer_to_first_user'),
            answer_to_second_user: $this->get('answer_to_second_user'),
            answer_to_third_user: $this->get('answer_to_third_user'),
            answer_to_fourth_user: $this->get('answer_to_fourth_user'),
            answer_to_fifth_user: $this->get('answer_to_fifth_user'),
            answer_to_sixth_user: $this->get('answer_to_sixth_user'),
        );
    }
}
