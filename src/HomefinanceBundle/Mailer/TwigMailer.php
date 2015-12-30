<?php

namespace HomefinanceBundle\Mailer;

use HomefinanceBundle\Entity\Share;
use HomefinanceBundle\User\Mailer\UserMailer;
use HomefinanceBundle\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class TwigMailer implements UserMailer {

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var RouterInterface
     */
    protected $router;

    protected $parameters;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, RouterInterface $router, $parameters)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
        $this->parameters = $parameters;
    }

    public function sendShareNotification(User $by, Share $share) {
        $template = 'HomefinanceBundle:mailer:administration_shared.txt.twig';
        $context = array(
            'by' => $by,
            'share' => $share,
        );
        $this->sendMessage($template, $context, $share->getUser()->getEmail());
    }

    public function sendConfirmationNewEmailAddressMail(User $user) {
        $template = 'HomefinanceBundle:mailer:confirm_email_address.txt.twig';
        $url = $this->router->generate('email_address_confirmation', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
            'plainUrl' => $this->router->generate('email_address_confirmation'),
        );
        $this->sendMessage($template, $context, $user->getNewEmail());
    }

    public function sendNewAccountConfirmationMail(User $user) {
        $template = 'HomefinanceBundle:mailer:new_account_confirmation.txt.twig';
        $url = $this->router->generate('new_account_confirmation', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
        );
        $this->sendMessage($template, $context, $user->getEmail());
    }

    public function sendConfirmationMail(User $user) {
        $template = 'HomefinanceBundle:mailer:confirmation.txt.twig';
        $url = $this->router->generate('email_address_confirmation', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
            'plainUrl' => $this->router->generate('email_address_confirmation'),
        );
        $this->sendMessage($template, $context, $user->getEmail());
    }

    public function lostPasswordMail(User $user) {
        $template = 'HomefinanceBundle:mailer:lost_password.txt.twig';
        $url = $this->router->generate('reset_password_check_token', array('token' => $user->getConfirmationToken()), true);
        $context = array(
            'user' => $user,
            'confirmationUrl' => $url
        );
        $this->sendMessage($template, $context, $user->getEmail());
    }

    public function resetPasswordMail(User $user) {
        $template = 'HomefinanceBundle:mailer:reset_password.txt.twig';
        $context = array(
            'user' => $user,
        );
        $this->sendMessage($template, $context, $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $toEmail)
    {
        $fromEmail = $this->parameters['from_email'];
        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = trim($template->renderBlock('text_body', $context));
        $htmlContent = trim($template->renderBlock('content_html_body', $context));
        $htmlBody = trim($template->renderBlock('html_body', $context));
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail['address'], $fromEmail['sender_name'])
            ->setTo($toEmail);
        $message->setBody($textBody);
        if (!empty($htmlBody) && !empty($htmlContent)) {
            $message->addPart($htmlBody, 'text/html');
        }
        $this->mailer->send($message);
    }

}