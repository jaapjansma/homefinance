<?php

namespace HomefinanceBundle\User\Controller;


use HomefinanceBundle\User\Model\ChangePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use HomefinanceBundle\User\Model\Profile;
use Symfony\Component\Validator\Constraints\Image;


class ProfileController extends Controller {

    /**
     * @route("/profile", name="profile")
     * @param Request $request
     * @return Response
     */
    public function userProfileAction(Request $request) {
        $factory = $this->get('homefinance.user.factory');
        $manager = $this->get('homefinance.user.manager');

        $user = $this->getUser();

        $profile = $factory->getProfile($user);
        $form = $this->createForm('profile', $profile);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->updateProfile($profile, $user);
            $this->addFlash('success', 'user.profile.updated');
        }

        if (!empty($this->getUser()->getNewEmail())) {
            $this->addFlash('info', 'user.profile.email_cofirmation_waiting');
        }

        return $this->render('HomefinanceBundle:user:profile.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }

    /**
     * @route("/profile/change-avatar", name="change-avatar")
     * @param Request $request
     * @return Response
     */
    public function uploadAvatarAction(Request $request) {
        $user = $this->getUser();
        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('avatar', 'file', array(
            'label' => $this->get('translator')->trans('change-avatar.file', array(), 'user'),
            'required' => false,
            'mapped' => false,
            'constraints' => array(
                new Image(),
            )
        ));
        $formBuilder->add('submit', 'submit', array(
            'label' => $this->get('translator')->trans('change-avatar.upload', array(), 'user'),
            'attr' => array(
                'class' => 'btn btn-lg btn-primary',
            ),
        ));
        $removeVisibleClass = '';
        if (!$user->hasAvatar()) {
            $removeVisibleClass = 'hidden';
        }
        $formBuilder->add('remove', 'button', array(
            'label' => $this->get('translator')->trans('change-avatar.remove', array(), 'user'),
            'attr' => array(
                'class' => 'btn btn-sm btn-danger remove '.$removeVisibleClass,
            ),
        ));

        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $avatar = $form['avatar']->getData();
            $dir = $this->get('kernel')->getRootDir() . '/../web/';
            $dir = realpath($dir);
            if ($user->hasAvatar() && file_exists($dir.'/'.$user->getAvatar())) {
                unlink($dir.'/'.$user->getAvatar());
            }
            $user->setAvatar(null);

            if ($form->getClickedButton()->getName() == 'submit' && !empty($avatar)) {
                $filename = sha1(uniqid($user->getId(), true)).'.'.$avatar->guessExtension();
                $avatar->move($dir . '/avatars', $filename);
                $user->setAvatar('avatars/' . $filename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('profile'));
        }

        return $this->render('HomefinanceBundle:user:avatar.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }

    /**
     * @route("/profile/change_password", name="profile_change_password")
     * @param Request $request
     * @return Response
     */
    public function changePasswordAction(Request $request) {
        $factory = $this->get('homefinance.user.factory');
        $manager = $this->get('homefinance.user.manager');

        $profile = $factory->getChangePassword($this->getUser());
        $form = $this->createForm('change_password', $profile);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->changePassword($profile);
            $this->addFlash('success', 'change_password.updated');
        }

        return $this->render('HomefinanceBundle:user:change_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}