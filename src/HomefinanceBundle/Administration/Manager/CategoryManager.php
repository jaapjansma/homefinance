<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Administration\Manager;

use HomefinanceBundle\Entity\Administration;
use Doctrine\ORM\EntityManager;
use HomefinanceBundle\Entity\Tag;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CategoryManager
{

    /**
     * @var EntityManager;
     */
    protected $entityManager;

    /**
     * @var AdministrationManager
     */
    protected $administrationManager;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    public function __construct(EntityManager $entityManager, AdministrationManager $administrationManager, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->administrationManager = $administrationManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function getEntityManager() {
        return $this->entityManager;
    }

    public function allToplevelOrLevelOne(Administration $administration) {
        if (!$administration) {
            $administration = $this->getCurrentAdministration();
        }
        if (!$administration) {
            return array();
        }
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Category');

        $categories = $repo->getChildrenByAdministration($administration, false, null, 'ASC', true);
        foreach($categories as $i => $category) {
            if ($category->getLevel() > 1) {
                unset($categories[$i]);
            }
        }
        return $categories;
    }

    public function allCategories(Administration $administration) {
        if (!$administration) {
            $administration = $this->getCurrentAdministration();
        }
        if (!$administration) {
            return array();
        }
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Category');

        $categories = $repo->getChildrenByAdministration($administration);
        $categories = $this->sortCategoriesAlphabetically($categories);

        return $categories;
    }

    public function allLeafCategories(Administration $administration=null) {
        if (!$administration) {
            $administration = $this->getCurrentAdministration();
        }
        if (!$administration) {
            return array();
        }
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Category');

        $categories = $repo->getChildrenByAdministration($administration);
        foreach($categories as $i => $category) {
            if (count($category->getChildren())) {
                unset($categories[$i]);
            }
        }

        $categories = $this->sortCategoriesAlphabetically($categories);

        return $categories;
    }

    protected function sortCategoriesAlphabetically($categories) {
        $unsortedCategories = array();
        foreach($categories as $category) {
            $unsortedCategories[$category->getIndentedTitle()] = $category;
        }
        ksort($unsortedCategories, SORT_NATURAL);
        $sortedCategories = array();
        foreach($unsortedCategories as $category) {
            $sortedCategories[$category->getId()] = $category;
        }
        return $sortedCategories;
    }

    protected function getCurrentAdministration() {
        $user = $this->getUser();
        if (!$user) {
            return false;
        }
        return $this->administrationManager->getCurrentAdministration($user);
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    protected function getUser()
    {
        if (!$this->tokenStorage) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }
}