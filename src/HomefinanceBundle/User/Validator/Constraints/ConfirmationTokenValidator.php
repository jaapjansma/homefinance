<?php

namespace HomefinanceBundle\User\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConfirmationTokenValidator extends ConstraintValidator {

    protected $entityManager;

    public function __construct(EntityManager $em) {
        $this->entityManager = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $user_repo = $this->entityManager->getRepository('HomefinanceBundle:User');
        $user = $user_repo->findOneBy(array('confirmationToken' => $value));
        $isValid = false;
        if ($user && $user->isTokenValid()) {
            $isValid = true;
        }

        if (!$isValid) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}