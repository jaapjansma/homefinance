<?php

namespace HomefinanceBundle\Administration\Manager;

use HomefinanceBundle\Administration\Manager\AdministrationManager;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Share\Permission;
use HomefinanceBundle\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessManager {

    /**
     * @var AdministrationManager
     */
    protected $manager;

    /**
     * @var null|TokenInterface
     */
    protected $token;

    /**
     * @var User
     */
    protected $user = false;

    public function __construct(AdministrationManager $manager) {
        $this->manager = $manager;
    }

    /**
     * @param Administration $administration
     * @param string $requiredPermission
     * @return bool
     */
    public function hasAccess(User $user, Administration $administration, $requiredPermission=Permission::EDIT) {
        $permission = $this->manager->determineAccess($administration, $user);
        if (Permission::hasPermission($requiredPermission, $permission)) {
            return true;
        }
        return false;
    }

    /**
     * @param $slug
     * @param string $requiredPermission
     * @return Administration|null
     * @throws EntityNotFoundException
     * @throws AccessDeniedException
     */
    public function getAdministrationBySlugWithAccess($slug, User $user, $requiredPermission=Permission::EDIT) {
        $administration = $this->manager->getAdministrationBySlug($slug);
        if (!$this->hasAccess($user, $administration, $requiredPermission)) {
            throw new AccessDeniedException();
        }
        return $administration;
    }

}