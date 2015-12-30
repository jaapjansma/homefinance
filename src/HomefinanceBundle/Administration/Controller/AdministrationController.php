<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Administration\Controller;

use HomefinanceBundle\Entity\BankAccount;
use HomefinanceBundle\Entity\Category;
use HomefinanceBundle\Share\Permission;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use HomefinanceBundle\DefaultController\DefaultController;

class AdministrationController extends DefaultController {

    /**
     * @Route("/switch/{slug}/", name="switch_administration")
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function switchAction(Request $request, $slug) {
        $user = $this->getUser();
        $accessManager = $this->get('homefinance.administration.access_manager');
        $administration = $accessManager->getAdministrationBySlugWithAccess($slug, $user, Permission::VIEW);

        $user->setCurrentAdministration($administration);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('dashboard'));
    }

    /**
     * @Route("/categories", name="categories")
     * @param Request $request
     * @return Response
     */
    public function categoriesAction(Request $request) {
        $user = $this->getUser();
        $manager = $this->get('homefinance.administration.manager');
        $administration = $this->checkCurrentAdministration(Permission::EDIT);

        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Category');
        $categories = $repo->getChildrenByAdministration($administration, false);

        return $this->render('HomefinanceBundle:Administration:categories.html.twig', array(
            'administration' => $administration,
            'categories' => $categories,
            'manager' => $manager,
            'user' => $user,
        ));
    }

    /**
     * @Route("/tags", name="tags")
     * @param Request $request
     * @return Response
     */
    public function tagsAction(Request $request) {
        $user = $this->getUser();
        $manager = $this->get('homefinance.administration.manager');
        $administration = $this->checkCurrentAdministration(Permission::EDIT);

        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Tag');
        $tags = $repo->findByAdministration($administration);

        return $this->render('HomefinanceBundle:Administration:tags.html.twig', array(
            'administration' => $administration,
            'tags' => $tags,
            'manager' => $manager,
            'user' => $user,
        ));
    }

    /**
     * @Route("/tags/add", name="add_tag")
     *
     * @param Request $request
     * @return Response
     */
    public function addTagAction(Request $request) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);

        $tag = new \HomefinanceBundle\Entity\Tag();
        $tag->setAdministration($administration);
        $form = $this->createForm('tag', $tag);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();
            $this->addFlash('success', 'tag.added');

            return $this->redirect($this->generateUrl('tags'));
        }

        return $this->render('HomefinanceBundle:Administration:add_tag.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/tags/{id}/edit", name="edit_tag")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editTagAction(Request $request, $id) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HomefinanceBundle:Tag');
        $tag = $repo->findOneByIdAndAdministration($administration, $id);
        $form = $this->createForm('tag', $tag);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($tag);
            $em->flush();
            $this->addFlash('success', 'tag.edited');

            return $this->redirect($this->generateUrl('tags'));
        }

        return $this->render('HomefinanceBundle:Administration:edit_tag.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/tags/{id}/delete", name="delete_tag")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function deleteTagAction(Request $request, $id) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HomefinanceBundle:Tag');
        $tag = $repo->findOneByIdAndAdministration($administration, $id);
        $em->remove($tag);
        $em->flush();

        $this->addFlash('success', 'tag.removed');

        return $this->redirect($this->generateUrl('tags'));
    }

    /**
     * @Route("/categories/add", name="add_category")
     *
     * @param Request $request
     * @return Response
     */
    public function addCategoryAction(Request $request) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);

        $category = new Category();
        $category->setAdministration($administration);
        $form = $this->createForm('category', $category);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('HomefinanceBundle:Category');
            $em->persist($category);
            $repo->verify();
            $repo->recover();
            $em->flush();
            $this->addFlash('success', 'category.added');

            return $this->redirect($this->generateUrl('categories'));
        }

        return $this->render('HomefinanceBundle:Administration:add_category.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/categories/{slug}/edit", name="edit_category")
     *
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function editCategoryAction(Request $request, $slug) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HomefinanceBundle:Category');
        $category = $repo->findOneBySlugAndAdministration($administration, $slug);
        $form = $this->createForm('category', $category);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($category);
            $repo->verify();
            $repo->recover();
            $em->flush();
            $this->addFlash('success', 'category.edited');

            return $this->redirect($this->generateUrl('categories'));
        }

        return $this->render('HomefinanceBundle:Administration:edit_category.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/categories/{slug}/move-up", name="move_category_up")
     *
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function moveCategoryUpAction(Request $request, $slug) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HomefinanceBundle:Category');
        $category = $repo->findOneBySlugAndAdministration($administration, $slug);
        $repo->moveUp($category);

        $this->addFlash('success', 'category.moved-up');

        return $this->redirect($this->generateUrl('categories'));
    }

    /**
     * @Route("/categories/{slug}/move-down", name="move_category_down")
     *
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function moveCategoryDownAction(Request $request, $slug) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HomefinanceBundle:Category');
        $category = $repo->findOneBySlugAndAdministration($administration, $slug);
        $repo->moveDown($category);

        $this->addFlash('success', 'category.moved-down');

        return $this->redirect($this->generateUrl('categories'));
    }

    /**
     * @Route("/categories/{slug}/delete", name="delete_category")
     *
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function deleteCategoryAction(Request $request, $slug) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HomefinanceBundle:Category');
        $category = $repo->findOneBySlugAndAdministration($administration, $slug);
        $repo->removeFromTree($category);

        $this->addFlash('success', 'category.removed');

        return $this->redirect($this->generateUrl('categories'));
    }

    /**
     * @Route("/bankaccounts", name="bank_accounts")
     * @param Request $request
     * @return Response
     */
    public function bankAccountsAction(Request $request) {
        $user = $this->getUser();
        $manager = $this->get('homefinance.administration.manager');
        $administration = $this->checkCurrentAdministration(Permission::EDIT);

        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:BankAccount');
        $accounts = $repo->findByAdministration($administration);

        return $this->render('HomefinanceBundle:Administration:bank_accounts.html.twig', array(
            'administration' => $administration,
            'accounts' => $accounts,
            'manager' => $manager,
            'user' => $user,
        ));
    }

    /**
     * @Route("/bankaccounts/add", name="add_bank_account")
     *
     * @param Request $request
     * @return Response
     */
    public function addBankAccountAction(Request $request) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);

        $account = new BankAccount();
        $account->setAdministration($administration);
        $form = $this->createForm('bank_account', $account);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();
            $this->addFlash('success', 'bank_account.added');

            return $this->redirect($this->generateUrl('bank_accounts'));
        }

        return $this->render('HomefinanceBundle:Administration:add_bank_account.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/bankaccounts/{id}/edit", name="edit_bank_account")
     *
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function editBankAccountAction(Request $request, $id) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HomefinanceBundle:BankAccount');
        $account = $repo->findOneByIdAndAdministration($administration, $id);
        $form = $this->createForm('bank_account', $account);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($account);
            $em->flush();
            $this->addFlash('success', 'bank_account.edited');

            return $this->redirect($this->generateUrl('bank_accounts'));
        }

        return $this->render('HomefinanceBundle:Administration:edit_bank_account.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/bankaccounts/{id}/delete", name="delete_bank_account")
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function deleteBankAccountAction(Request $request, $id) {
        $administration = $this->checkCurrentAdministration(Permission::FULL_ACCESS);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HomefinanceBundle:BankAccount');
        $account = $repo->findOneByIdAndAdministration($administration, $id);
        $em->remove($account);
        $em->flush();

        $this->addFlash('success', 'bank_account.removed');

        return $this->redirect($this->generateUrl('bank_accounts'));
    }

}