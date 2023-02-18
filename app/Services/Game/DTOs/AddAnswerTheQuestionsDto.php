<?php

namespace App\Services\Game\DTOs;

class AddAnswerTheQuestionsDto
{
    /**
     * @param ?string $answerToFirstUser
     * @param ?string $answerToSecondUser
     * @param ?string $answerToThirdUser
     * @param ?string $answerToFourthUser
     * @param ?string $answerToFifthUser
     * @param ?string $answerToSixthUser
     */
    public function __construct(
        public ?string $answerToFirstUser,
        public ?string $answerToSecondUser,
        public ?string $answerToThirdUser,
        public ?string $answerToFourthUser,
        public ?string $answerToFifthUser,
        public ?string $answerToSixthUser,
    )
    {
    }
}
