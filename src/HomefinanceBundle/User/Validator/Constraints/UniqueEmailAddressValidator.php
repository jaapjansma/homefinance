<?php

namespace HomefinanceBundle\User\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailAddressValidator extends ConstraintValidator {

    /**
     * @var EntityManager
     */
    protected $entityManager;

    protected $token;

    public function __construct(EntityManager $em, TokenStorageInterface $token) {
        $this->entityManager = $em;
        $this->token = $token->getToken();
    }

    public function validate($value, Constraint $constraint)
    {
        $query = $this->entityManager->createQueryBuilder();
        $query->select('user');
        $query->from('HomefinanceBundle:User', 'user');
        if ($this->token->getUser() != "anon." && $this->token->getUser()->getId()) {
            $query->where( 'user.id != :user_id AND (user.email = :email OR user.newEmail = :email)');
            $query->setParameter('user_id', $this->token->getUser()->getId());
        } else {
            $query->where( 'user.email = :email OR user.newEmail = :email');
        }
        $query->setParameter('email', $value);
        $user = $query->getQuery()->getOneOrNullResult();

        if ($user) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%e-mail%', $value)
                ->addViolation();
        }
    }

}