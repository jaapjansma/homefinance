<?php

namespace HomefinanceBundle\User\Mailer;
use HomefinanceBundle\Entity\Share;
use HomefinanceBundle\Entity\User;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

interface UserMailer {

    public function sendConfirmationMail(User $user);

    public function sendNewAccountConfirmationMail(User $user);

    public function sendConfirmationNewEmailAddressMail(User $user);

    public function lostPasswordMail(User $user);

    public function resetPasswordMail(User $user);

    public function sendShareNotification(User $by, Share $share);

}