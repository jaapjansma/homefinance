<?php

namespace HomefinanceBundle\User\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEmailAddress extends Constraint {

    public $message = "The e-mail address '%e-mail% is not unique";

    public function validatedBy()
    {
        return 'unique_email';
    }

}