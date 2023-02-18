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
            'answer_to_first_user' => ['string', 'max:120', 'nullable'],
            'answer_to_second_user' => ['string', 'max:120', 'nullable'],
            'answer_to_third_user' => ['string', 'max:120', 'nullable'],
            'answer_to_fourth_user' => ['string', 'max:120', 'nullable'],
            'answer_to_fifth_user' => ['string', 'max:120', 'nullable'],
            'answer_to_sixth_user' => ['string', 'max:120', 'nullable'],
        ];
    }

    /**
     * @return AddAnswerTheQuestionsDto
     */
    final public function getDto(): AddAnswerTheQuestionsDto
    {
        return new AddAnswerTheQuestionsDto(
            answerToFirstUser: $this->get('answer_to_first_user'),
            answerToSecondUser: $this->get('answer_to_second_user'),
            answerToThirdUser: $this->get('answer_to_third_user'),
            answerToFourthUser: $this->get('answer_to_fourth_user'),
            answerToFifthUser: $this->get('answer_to_fifth_user'),
            answerToSixthUser: $this->get('answer_to_sixth_user'),
        );
    }
}
