<?php

namespace HomefinanceBundle\User\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailExists extends Constraint {

    public $message = "The e-mail address %e-mail% doesn't exist";

    public function validatedBy()
    {
        return 'email_exists';
    }

}