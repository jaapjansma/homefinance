<?php

namespace HomefinanceBundle\User\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmailExistsValidator extends ConstraintValidator {

    protected $entityManager;

    public function __construct(EntityManager $em) {
        $this->entityManager = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query->select('user');
        $query->from('HomefinanceBundle:User', 'user');
        $query->where( 'user.email = :email OR user.newEmail = :email');
        $query->setParameter('email', $value);
        $user = $query->getQuery()->getOneOrNullResult();

        if (!$user) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%e-mail%', $value)
                ->addViolation();
        }
    }

}