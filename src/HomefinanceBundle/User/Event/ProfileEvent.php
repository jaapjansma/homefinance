<?php

namespace HomefinanceBundle\User\Event;

use HomefinanceBundle\User\Event\UserEvent;
use HomefinanceBundle\User\Entity\User;
use HomefinanceBundle\User\Model\Profile;

class ProfileEvent extends UserEvent {

    /**
     * @var Profile
     */
    protected $profile;

    public function __construct(User $user, Profile $profile) {
        parent::__construct($user);
        $this->profile = $profile;
    }

    /**
     * @return Profile
     */
    public function getProfile() {
        return $this->profile;
    }

}