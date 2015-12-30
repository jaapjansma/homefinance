<?php

namespace HomefinanceBundle\User\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidConfirmationToken extends Constraint {

    public $message = "The token does not exist or is expired";

    public function validatedBy()
    {
        return 'valid_confirmation_token';
    }

}