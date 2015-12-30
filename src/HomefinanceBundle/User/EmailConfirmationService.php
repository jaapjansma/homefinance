<?php

namespace HomefinanceBundle\User;

use HomefinanceBundle\Administration\AdministrationEvents;
use HomefinanceBundle\Administration\Event\ShareEvent;
use HomefinanceBundle\User\Event\RegistrationEvent;
use HomefinanceBundle\User\Event\UserEvent;
use HomefinanceBundle\User\Entity\User;
use HomefinanceBundle\User\Mailer\UserMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class EmailConfirmationService implements EventSubscriberInterface
{

    /**
     * @var UserMailer
     */
    protected $mailer;

    /**
     * @var TokenGeneratorInterface
     */
    protected $tokenGenerator;

    public function __construct(UserMailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
    }

    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::EMAIL_REGISTRATION_SUCCESS => 'onEmailRegistrationSuccess',
            UserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            UserEvents::LOST_PASSWORD => 'onLostPassword',
            UserEvents::RESET_PASSWORD_SUCCESS => 'onResetPassword',
            UserEvents::NEW_EMAIL_ADDRESS => 'onNewEmailAddress',
            AdministrationEvents::ADMINISTRATION_SHARED => 'onAdministrationShared',
        );
    }

    public function onAdministrationShared(ShareEvent $event) {
        $user = $event->getBy();
        $share = $event->getShare();
        $this->mailer->sendShareNotification($user, $share);
    }

    public function onNewEmailAddress(UserEvent $event) {
        $user = $event->getUser();
        $this->setToken($user);
        $user->setConfirmationRequestedAt(new \DateTime());
        $this->mailer->sendConfirmationNewEmailAddressMail($user);
    }

    public function onLostPassword(UserEvent $event) {
        $user = $event->getUser();
        $this->setToken($user);
        $this->mailer->lostPasswordMail($user);
    }

    public function onEmailRegistrationSuccess(UserEvent $event) {
        $user = $event->getUser();
        $this->setToken($user);
        $user->setConfirmationRequestedAt(new \DateTime());
        $this->mailer->sendNewAccountConfirmationMail($user);
    }

    public function onRegistrationSuccess(RegistrationEvent $event) {
        $user = $event->getUser();
        $this->setToken($user);
        $user->setConfirmationRequestedAt(new \DateTime());
        $this->mailer->sendConfirmationMail($user);
    }

    public function onResetPassword(UserEvent $event) {
        $user = $event->getUser();
        $this->mailer->resetPasswordMail($user);
    }

    protected function setToken(User $user) {
        if (!$user->isTokenValid()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }
        $user->setConfirmationTokenValidTill($this->getValidTill());
        return $user;
    }

    protected function getValidTill() {
        $date = new \DateTime();
        $date->modify('+2 weeks');
        return $date;
    }
}