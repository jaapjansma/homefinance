<?php

namespace HomefinanceBundle\User\Manager;

use Doctrine\ORM\EntityNotFoundException;
use HomefinanceBundle\Category\Preset;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\User\Event\ProfileEvent;
use HomefinanceBundle\User\Model\ResetPassword;
use HomefinanceBundle\User\UserEvents;
use HomefinanceBundle\Entity\User;
use HomefinanceBundle\User\Entity\User as BaseUser;
use HomefinanceBundle\User\Event\UserEvent;
use HomefinanceBundle\User\Event\RegistrationEvent;
use HomefinanceBundle\User\Model\ChangePassword;
use HomefinanceBundle\User\Model\Profile;
use HomefinanceBundle\User\Model\Registration;
use HomefinanceBundle\User\Model\LostPassword;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserManager implements UserManagerInterface
{

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var EntityManager;
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager, EventDispatcherInterface $event_dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->dispatcher = $event_dispatcher;
    }

    public function registerNewUser(Registration $registration) {
        $user = new User();
        $administration = new Administration();
        $administration->setOwner($user);

        $registration->updateUser($user);
        $registration->updateAdministration($administration);

        $user->setEnabled(false);

        $categoryPreset = new Preset();
        $rootCategory = $categoryPreset->createPreset($administration);


        $event = new RegistrationEvent($user, $registration);
        $this->dispatcher->dispatch(UserEvents::REGISTRATION_SUCCESS, $event);

        $this->entityManager->persist($user);
        $this->entityManager->persist($administration);
        $this->entityManager->persist($rootCategory);
        $this->entityManager->flush();
    }

    /**
     * @param LostPassword $lostPassword
     * @throws EntityNotFoundException
     */
    public function requestNewPassword(LostPassword $lostPassword) {
        $user = $this->findUserByEmail($lostPassword->getEmail());

        $user->setPasswordRequestedAt(new \DateTime());

        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvents::LOST_PASSWORD, $event);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function resetPassword(ResetPassword $resetPassword, BaseUser $user) {
        $user->setConfirmationTokenValidTill(null);
        $user->setConfirmationToken(null);

        $resetPassword->updateUser($user);
        $this->resetTokenData($user);
        $user->setEnabled(true);
        $user->setPasswordRequestedAt(null);

        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvents::RESET_PASSWORD_SUCCESS, $event);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function findUserByEmail($email) {
        $query = $this->entityManager->createQueryBuilder();
        $query->select('user');
        $query->from('HomefinanceBundle:User', 'user');
        $query->where( 'user.email = :email OR user.newEmail = :email');
        $query->setParameter('email', $email);
        $user = $query->getQuery()->getOneOrNullResult();
        if (!$user) {
            throw new EntityNotFoundException();
        }
        return $user;
    }

    public function createOrFindUserByEmail($email) {
        $user = false;
        try {
            $user = $this->findUserByEmail($email);
        } catch (EntityNotFoundException $e) {
            //user not found create one
        }

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setEnabled(false);

            $event = new UserEvent($user);
            $this->dispatcher->dispatch(UserEvents::EMAIL_REGISTRATION_SUCCESS, $event);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $user;
    }

    public function updateProfile(Profile $profile, BaseUser $user) {
        if ($profile->getEmail() == $user->getEmail() && !empty($user->getNewEmail())) {
            //email address is reset by the user
            $user->setNewEmail(null);
            $this->resetTokenData($user);
            $profile->updateUser($user);

            $event = new UserEvent($user);
            $this->dispatcher->dispatch(UserEvents::RESET_NEW_EMAIL_ADDRESS, $event);

        } elseif ($profile->getEmail() != $user->getEmail()) {
            $originalEmail = $user->getEmail();
            $profile->updateUser($user);
            $user->setNewEmail($user->getEmail());
            $user->setEmail($originalEmail);

            $event = new UserEvent($user);
            $this->dispatcher->dispatch(UserEvents::NEW_EMAIL_ADDRESS, $event);

        } else {
            $profile->updateUser($user);
        }

        $event = new ProfileEvent($user, $profile);
        $this->dispatcher->dispatch(UserEvents::PROFILE_UPDATED, $event);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function changePassword(ChangePassword $changePassword) {
        $user = $changePassword->getUpdatedUser();

        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvents::PASSWORD_CHANGED, $event);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function emailAddressConfirmed(BaseUser $user) {
        if (!empty($user->getNewEmail())) {
            $user->setEmail($user->getNewEmail());
            $user->setNewEmail(null);
        }

        $user->setEnabled(true);
        $this->resetTokenData($user);

        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvents::CONFIRMATION_SUCCESS, $event);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function findUserByToken($token) {
        $user_repo = $this->entityManager->getRepository('HomefinanceBundle:User');
        $user = $user_repo->findOneBy(array('confirmationToken' => $token));
        if ($user && $user->isTokenValid()) {
            return $user;
        }
        throw new EntityNotFoundException();
    }

    protected function resetTokenData(BaseUser $user) {
        $user->setConfirmationRequestedAt(null);
        $user->setConfirmationToken(null);
        $user->setConfirmationTokenValidTill(null);
    }
}