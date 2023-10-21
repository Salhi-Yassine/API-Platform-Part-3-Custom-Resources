<?php

namespace App\Validator;

use App\Entity\CheeseListing;
use LogicException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidIsPublishedValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!($value instanceof CheeseListing) ) {
            throw new LogicException("Only CheeseListing is supported.");
        }
        /* @var $constraint \App\Validator\ValidIsPublished */

        if (null === $value || '' === $value) {
            return;
        }

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}