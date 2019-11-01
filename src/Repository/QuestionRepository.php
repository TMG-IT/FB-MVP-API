<?php

namespace App\Repository;

use App\Entity\Question\Question;

class QuestionRepository extends BaseRepository
{
    public const ENTITY_CLASS_NAME = Question::class;
}
