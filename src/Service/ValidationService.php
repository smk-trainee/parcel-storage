<?php

namespace App\Service;

use App\Dto\ParcelDto;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(?ParcelDto $dto): ConstraintViolationListInterface
    {
        return $this->validator->validate($dto);
    }
}
