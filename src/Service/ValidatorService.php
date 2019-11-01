<?php

namespace App\Service;

use App\Exception\ApiValidationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidatorService
{
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    /**
     * @throws \App\Exception\VerboseExceptionInterface
     */
    public function validate($object, array $groups): void
    {
        $errors = [];

        if (\count($validationErrors = $this->validator->validate($object, null, $groups)) > 0) {
            /** @var ConstraintViolation $error */
            foreach ($validationErrors as $error) {
                // TODO: consider using serialized name to send instead of property path
                $errors[$error->getPropertyPath()] = $error->getMessage();
            }

            throw ApiValidationException::create('Validation failed', $errors);
        }
    }
}
