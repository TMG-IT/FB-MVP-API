<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvalidSessionCodeException extends NotFoundHttpException
{
}
