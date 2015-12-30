<?php

namespace HomefinanceBundle\User\Factory;

use HomefinanceBundle\User\Model\Profile;
use HomefinanceBundle\User\Model\Registration;
use HomefinanceBundle\User\Model\ChangePassword;
use HomefinanceBundle\User\Model\Confirmation;
use HomefinanceBundle\User\Model\LostPassword;;
use HomefinanceBundle\User\Model\ResetPassword;;
use HomefinanceBundle\User\Entity\User;


interface FactoryInterface {

    /**
     * @param User $user
     * @return Profile
     */
    public function getProfile(User $user);

    /**
     * @param User $user
     * @return ChangePassword
     */
    public function getChangePassword(User $user);

    /**
     * @param $token optional
     * @return Confirmation
     */
    public function getAccountConfirmation($token=null);

    /**
     * @return Registration
     */
    public function getRegistration();

    /**
     * @return LostPassword
     */
    public function getLostPassword();

    /**
     * @return ResetPassword
     */
    public function getResetPassword();

}