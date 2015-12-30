<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Administration\Controller;

use HomefinanceBundle\Administration\AdministrationEvents;
use HomefinanceBundle\Administration\Event\ShareEvent;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Entity\BankAccount;
use HomefinanceBundle\Entity\Category;
use HomefinanceBundle\Entity\Share;
use HomefinanceBundle\Share\Permission;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use HomefinanceBundle\DefaultController\DefaultController;

class ManageController extends DefaultController {

    /**
     * @Route("/administrations", name="manage_administrations")
     * @param Request $request
     * @return Response
     */
    public function administrationsAction(Request $request) {
        $user = $this->getUser();
        $manager = $this->get('homefinance.administration.manager');
        $administrations = $manager->listUserAdministrations($user);
        return $this->render('HomefinanceBundle:Administration:manager.html.twig', array(
            'administrations' => $administrations,
            'manager' => $manager,
            'user' => $user,
        ));
    }

    /**
     * @Route("/administrations/{slug}/delete", name="delete_administration")
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function deleteAction(Request $request, $slug) {
        $user = $this->getUser();
        $accessManager = $this->get('homefinance.administration.access_manager');
        $administration = $accessManager->getAdministrationBySlugWithAccess($slug, $user, Permission::OWNER);
        if ($administration) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($administration);
            $em->flush();
            $this->addFlash('success', 'administration.deleted');
        }
        return $this->redirect($this->generateUrl('manage_administrations'));
    }

    /**
     * @Route("/administrations/{slug}/edit", name="edit_administration")
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function editAction(Request $request, $slug) {
        $user = $this->getUser();
        $accessManager = $this->get('homefinance.administration.access_manager');
        $administration = $accessManager->getAdministrationBySlugWithAccess($slug, $user, Permission::FULL_ACCESS);
        $form = $this->createForm('administration_edit', $administration);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($administration);
            $em->flush();
            $this->addFlash('success', 'administration.updated');

            return $this->redirect($this->generateUrl('manage_administrations'));
        }

        return $this->render('HomefinanceBundle:Administration:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/administrations/new", name="new_administration")
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request) {
        $user = $this->getUser();
        $administration = new Administration();
        $administration->setOwner($user);
        $form = $this->createForm('administration_edit', $administration);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($administration);
            $em->flush();
            $this->addFlash('success', 'administration.updated');

            return $this->redirect($this->generateUrl('manage_administrations'));
        }

        return $this->render('HomefinanceBundle:Administration:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/administrations/{slug}/shares", name="share_administration")
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function sharesAction(Request $request, $slug) {
        $user = $this->getUser();
        $accessManager = $this->get('homefinance.administration.access_manager');
        $administration = $accessManager->getAdministrationBySlugWithAccess($slug, $user, Permission::FULL_ACCESS);
        $manager = $this->get('homefinance.administration.manager');

        return $this->render('HomefinanceBundle:Administration:shares.html.twig', array(
            'administration' => $administration,
            'manager' => $manager,
        ));
    }

    /**
     * @Route("/administrations/{slug}/shares/new", name="share_administration_add")
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function newShareAction(Request $request, $slug) {
        $user = $this->getUser();
        $accessManager = $this->get('homefinance.administration.access_manager');
        $administration = $accessManager->getAdministrationBySlugWithAccess($slug, $user, Permission::FULL_ACCESS);
        $share = new Share();
        $share->setAdministration($administration);
        $form = $this->createForm('share_edit', $share);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $share->setPermission($form->get('permission')->getData());
            $userManager = $this->get('homefinance.user.manager');
            $share->setUser($userManager->createOrFindUserByEmail($form->get('email')->getData()));

            $doNotSave = false;
            if ($administration->getOwner() == $share->getUser()) {
                $doNotSave = true;
            } else {
                foreach($administration->getShares() as $s) {
                    if ($s->getId() && $s->getUser() == $share->getUser()) {
                        $doNotSave = true;
                        break;
                    }
                }
            }

            if (!$doNotSave) {
                $event = new ShareEvent($share, $user);
                $this->get('event_dispatcher')->dispatch(AdministrationEvents::ADMINISTRATION_SHARED, $event);

                $em = $this->getDoctrine()->getManager();
                $em->persist($share);
                $em->flush();

                $this->addFlash('success', 'administration.share.added');
            }

            return $this->redirect($this->generateUrl('share_administration', array('slug' => $administration->getSlug())));
        }

        return $this->render('HomefinanceBundle:Administration:new_share.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/administrations/{slug}/shares/revoke/{id}", name="share_administration_revoke")
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function revokeShareAction(Request $request, $slug, $id) {
        $user = $this->getUser();
        $accessManager = $this->get('homefinance.administration.access_manager');
        $administration = $accessManager->getAdministrationBySlugWithAccess($slug, $user, Permission::FULL_ACCESS);
        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Share');
        $share = $repo->findOneBy(array(
            'administration' => $administration,
            'id' => $id
        ));


        $event = new ShareEvent($share, $user);
        $this->get('event_dispatcher')->dispatch(AdministrationEvents::ADMINISTRATION_REVOKED, $event);

        $em = $this->getDoctrine()->getManager();
        $em->remove($share);
        $em->flush();

        $this->addFlash('success', 'administration.share.revoked');

        return $this->redirect($this->generateUrl('share_administration', array('slug' => $administration->getSlug())));
    }

}