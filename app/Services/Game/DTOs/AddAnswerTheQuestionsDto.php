<?php

namespace App\Services\Game\DTOs;

class AddAnswerTheQuestionsDto
{
    /**
     * @param string|null $answer_to_first_user
     * @param string|null $answer_to_second_user
     * @param string|null $answer_to_third_user
     * @param string|null $answer_to_fourth_user
     * @param string|null $answer_to_fifth_user
     * @param string|null $answer_to_sixth_user
     */
    public function __construct(
        public string|null $answer_to_first_user,
        public string|null $answer_to_second_user,
        public string|null $answer_to_third_user,
        public string|null $answer_to_fourth_user,
        public string|null $answer_to_fifth_user,
        public string|null $answer_to_sixth_user,
    )
    {
    }
}
