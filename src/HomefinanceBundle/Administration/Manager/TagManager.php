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

class TagManager
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

    public function listAllTags() {
        $user = $this->getUser();
        if (!$user) {
            return array();
        }
        $administration = $this->administrationManager->getCurrentAdministration($user);
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Tag');
        $tags = $repo->findByAdministration($administration);
        return $tags;
    }

    public function loadOrCreateTags($tags) {
        $user = $this->getUser();
        if (!$user) {
            return array();
        }
        $administration = $this->administrationManager->getCurrentAdministration($user);
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Tag');

        $values = array();
        foreach($tags as $tag) {
            $t = $repo->findOneByNameAndAdministration($administration, $tag);
            if (!$t) {
                $t = new Tag();
                $t->setAdministration($administration);
                $t->setName($tag);
                $this->entityManager->persist($t);
            }
            $values[] = $t;
        }
        return $values;
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