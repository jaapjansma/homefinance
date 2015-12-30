<?php

namespace HomefinanceBundle\User\Event;

use HomefinanceBundle\User\Event\UserEvent;
use HomefinanceBundle\User\Entity\User;
use HomefinanceBundle\User\Model\Registration;

class RegistrationEvent extends UserEvent {

    /**
     * @var Registration
     */
    protected $registration;

    public function __construct(User $user, Registration $registration) {
        parent::__construct($user);
        $this->registration = $registration;
    }

    /**
     * @return Registration
     */
    public function getRegistration() {
        return $this->registration;
    }

}