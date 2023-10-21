<?php

namespace App\Validator;

use App\Entity\CheeseListing;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidIsPublishedValidator extends ConstraintValidator
{
    private $entityManager;
    private $security;
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!($value instanceof CheeseListing) ) {
            throw new LogicException("Only CheeseListing is supported.");
        }
        /* @var $constraint \App\Validator\ValidIsPublished */

        $originalData = $this->entityManager->getUnitOfWork()->getOriginalEntityData($value);
        $previousIsPublishes = $originalData['isPublished'] ?? false;

        if ($previousIsPublishes === $value->getIsPublished()) {
            // isPublished didn't change!
            return;
        }

        if ($value->getIsPublished()) {
            // we are publishing

            // don't allow short description, unless you are an admin
            if (strlen($value->getDescription()) < 100 && !$this->security->isGranted('ROLE_ADMIN')) {
                $this->context->buildViolation('Cannot publish: description is too short!')
                        ->atPath('description') //  make the validation failure look like it's attached to
                                                //  the description field even though we
                                                //  added the constraint to the entire class
                        ->addViolation();
            }
            // it is an admin no volation
            return;
        }

        // we are unpublishing
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            // use it if you want to retur 403 status code to the client for unauthorased request.
            // throw new AccessDeniedException('only admin user can unpulbish');
            $this->context->buildViolation('only admin user can unpublish')
            ->addViolation();
        }
        

    }

}
