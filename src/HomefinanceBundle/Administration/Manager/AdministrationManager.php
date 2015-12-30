<?php

namespace HomefinanceBundle\Administration\Manager;

use Doctrine\ORM\EntityNotFoundException;
use HomefinanceBundle\Administration\Exception\NoAdministrationException;
use HomefinanceBundle\Share\Permission;
use HomefinanceBundle\User\Entity\User as BaseUser;
use HomefinanceBundle\Entity\Administration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;

class AdministrationManager {

    /**
     * @var EntityManager;
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCurrentAdministration(BaseUser $user) {
        $administrations = $this->listUserAdministrations($user);
        if (empty($administrations)) {
            throw new NoAdministrationException();
        }

        $currentAdministration = $user->getCurrentAdministration();
        if ($currentAdministration) {
            foreach($administrations as $administration) {
                if ($administration == $currentAdministration) {
                    return $currentAdministration;
                }
            }
        }

        return reset($administrations);
    }

    /**
     * @param BaseUser $user
     * @return array
     */
    public function listUserAdministrations(BaseUser $user) {
        $query = $this->entityManager->createQueryBuilder();
        $query->select('administration');
        $query->from('HomefinanceBundle:Administration', 'administration');
        $query->leftJoin('HomefinanceBundle:Share', 'share', Join::LEFT_JOIN, 'share.administration = administration');
        $query->where('administration.owner = :owner');
        $query->orWhere(
            $query->expr()->andX('share.user = :owner', 'administration.owner != :owner')
        );
        $query->orderBy('administration.name');
        $query->setParameter('owner', $user);
        return $query->getQuery()->getResult();
    }

    /**
     * @param Administration $administration
     * @param BaseUser $user
     * @return string
     */
    public function determineAccess(Administration $administration, BaseUser $user) {
        if ($administration->getOwner() == $user) {
            return Permission::OWNER;
        }
        foreach($administration->getShares() as $share) {
            if ($share->getUser() == $user) {
                return $share->getPermission();
            }
        }
        return Permission::NO_ACCESS;
    }

    /**
     * @param $slug
     * @return null|Administration
     * @throws EntityNotFoundException
     */
    public function getAdministrationBySlug($slug) {
        $administration = $this->entityManager->getRepository('HomefinanceBundle:Administration')->findOneBy(array(
            'slug' => $slug,
        ));
        if (!$administration) {
            throw new EntityNotFoundException();
        }
        return $administration;
    }

}