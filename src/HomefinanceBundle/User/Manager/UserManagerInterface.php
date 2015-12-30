<?php

namespace HomefinanceBundle\User\Manager;

use HomefinanceBundle\User\Entity\User;
use HomefinanceBundle\User\Model\LostPassword;
use HomefinanceBundle\User\Model\Profile;
use HomefinanceBundle\User\Model\ChangePassword;
use HomefinanceBundle\User\Model\Registration;
use Doctrine\ORM\EntityNotFoundException;
use HomefinanceBundle\User\Model\ResetPassword;

interface UserManagerInterface {

    /**
     * Register a new user in the system
     *
     * @param Registration $registration
     * @return User
     */
    public function registerNewUser(Registration $registration);

    /**
     * Reuqests a new password
     *
     * @param LostPassword $lostPassword
     * @return void
     */
    public function requestNewPassword(LostPassword $lostPassword);

    /**
     * Resets the password of the user
     *
     * @param ResetPassword $resetPassword
     * @return void
     */
    public function resetPassword(ResetPassword $resetPassword, User $user);

    /**
     * @param $email
     * @return User
     * @throws EntityNotFoundException
     */
    public function findUserByEmail($email);

    /**
     * @param $email
     * @return User
     */
    public function createOrFindUserByEmail($email);

    /**
     * Update the user object with data from the profile object and persist the user
     *
     * @param Profile $profile
     * @param User $user;
     * @return void
     */
    public function updateProfile(Profile $profile, User $user);

    /**
     * Changes the password for the user and stores the user
     *
     * @param ChangePassword $changePassword
     * @return void
     */
    public function changePassword(ChangePassword $changePassword);


    /**
     * Confirm e-mail address of a user
     *
     * @param User $user
     * @return void
     */
    public function emailAddressConfirmed(User $user);

    /**
     * Returns the User object identified by token
     *
     * @param string $token
     * @return User|false
     */
    public function findUserByToken($token);


}