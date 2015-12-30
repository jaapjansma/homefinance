<?php

namespace HomefinanceBundle\User\Controller;

use Doctrine\ORM\EntityNotFoundException;
use HomefinanceBundle\User\Model\LostPasswordNewPassword;
use HomefinanceBundle\User\Model\ResetPassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use HomefinanceBundle\User\Registration;

class SecurityController extends Controller {

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // replace this example code with whatever you need
        return $this->render(
            'HomefinanceBundle:user:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/new-account-confirmation/{token}", name="new_account_confirmation")
     * @param Request $request
     * @param $token
     * @return Response
     */
    public function newAccountConfirmationAction(Request $request, $token) {
        $manager = $this->get('homefinance.user.manager');
        $resetPassword = $this->get('homefinance.user.factory')->getResetPassword();
        $resetPassword->setConfirmationToken($token);

        $user = false;
        if (!empty($resetPassword->getConfirmationToken())) {
            try {
                $user = $manager->findUserByToken($resetPassword->getConfirmationToken());
            } catch (EntityNotFoundException $entityNotFound) {
                //do nothing
            }
        }
        if (!$user) {
            return $this->redirect($this->generateUrl('reset_password_check_token', array(
                'token' => $resetPassword->getConfirmationToken(),
            )));
        }

        $form = $this->createForm('reset_password', $resetPassword);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $manager->resetPassword($resetPassword, $user);
            return $this->render('HomefinanceBundle:user:reset_password_success.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        return $this->render('HomefinanceBundle:user:new_account_confirmation.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/email-address-confirmation/{token}", defaults={"token"=null}, name="email_address_confirmation")
     * @param Request $request
     * @param $token
     * @return Response
     */
    public function emailAddressConfirmationAction(Request $request, $token=null) {
        $factory = $this->get('homefinance.user.factory');
        $manager = $this->get('homefinance.user.manager');
        $confirmation = $factory->getAccountConfirmation($token);

        $form = $this->createForm('confirmation', $confirmation);
        $form->handleRequest($request);

        $user = false;
        if (!empty($confirmation->getConfirmationToken())) {
            try {
                $user = $manager->findUserByToken($confirmation->getConfirmationToken());
            } catch (EntityNotFoundException $entityNotFound) {
                //do nothing
            }
        }

        if ($user) {
            $manager->emailAddressConfirmed($user);
            return $this->render('HomefinanceBundle:user:confirmation_success.html.twig');
        }


        return $this->render('HomefinanceBundle:user:confirmation.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request) {
        $registration = $this->get('homefinance.user.factory')->getRegistration();
        $form = $this->createForm('registration', $registration);
        $form->handleRequest($request);
        if ($form->isValid()) {
            //form is valid
            $this->get('homefinance.user.manager')->registerNewUser($registration);
            return $this->render('HomefinanceBundle:user:registration_success.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        return $this->render('HomefinanceBundle:user:registration.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/lost-password", name="lost_password")
     * @param Request $request
     * @return Response
     */
    public function lostPasswordAction(Request $request) {
        $lost_password = $this->get('homefinance.user.factory')->getLostPassword();
        $form = $this->createForm('lost_password', $lost_password);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('homefinance.user.manager')->requestNewPassword($lost_password);
            return $this->render('HomefinanceBundle:user:lost_password_success.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        return $this->render('HomefinanceBundle:user:lost_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/reset-password/check-token/{token}", defaults={"token"=null}, name="reset_password_check_token")
     * @param Request $request
     * @param $token
     * @return Response
     */
    public function resetPasswordCheckTokenAction(Request $request, $token=null) {
        $manager = $this->get('homefinance.user.manager');
        $resetPassword = $this->get('homefinance.user.factory')->getResetPassword();
        $resetPassword->setConfirmationToken($token);
        $form = $this->createForm('reset_password_token', $resetPassword);
        $form->handleRequest($request);

        $user = false;
        if (!empty($resetPassword->getConfirmationToken())) {
            try {
                $user = $manager->findUserByToken($resetPassword->getConfirmationToken());
            } catch (EntityNotFoundException $entityNotFound) {
                //do nothing
            }
        }

        if ($user) {
            return $this->redirect($this->generateUrl('reset_password', array(
                'token' => $resetPassword->getConfirmationToken(),
            )));
        }


        return $this->render('HomefinanceBundle:user:reset_password_token.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/reset-password/token/{token}", name="reset_password")
     * @param Request $request
     * @param $token
     * @return Response
     */
    public function lostPasswordNewPasswordAction(Request $request, $token) {
        $manager = $this->get('homefinance.user.manager');
        $resetPassword = $this->get('homefinance.user.factory')->getResetPassword();
        $resetPassword->setConfirmationToken($token);

        $user = false;
        if (!empty($resetPassword->getConfirmationToken())) {
            try {
                $user = $manager->findUserByToken($resetPassword->getConfirmationToken());
            } catch (EntityNotFoundException $entityNotFound) {
                //do nothing
            }
        }
        if (!$user) {
            return $this->redirect($this->generateUrl('reset_password_check_token', array(
                'token' => $resetPassword->getConfirmationToken(),
            )));
        }

        $form = $this->createForm('reset_password', $resetPassword);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $manager->resetPassword($resetPassword, $user);
            return $this->render('HomefinanceBundle:user:reset_password_success.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        return $this->render('HomefinanceBundle:user:reset_password_token.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}