<?php

namespace HomefinanceBundle\Twig;

use HomefinanceBundle\Administration\Manager\AccessManager;
use HomefinanceBundle\Administration\Manager\AdministrationManager;
use HomefinanceBundle\Administration\Manager\TransactionManager;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Share\Permission;
use HomefinanceBundle\User\Entity\User;

class AdministrationExtension extends \Twig_Extension
{

    /**
     * @var AccessManager
     */
    protected $accessManager;

    /**
     * @var AdministrationManager
     */
    protected $administrationManager;

    /**
     * @var TransactionManager
     */
    protected $transactionManager;

    public function __construct(AccessManager $accessManager, AdministrationManager $administrationManager, TransactionManager $transactionManager) {
        $this->accessManager = $accessManager;
        $this->administrationManager = $administrationManager;
        $this->transactionManager = $transactionManager;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('has_access_to_administration', array($this, 'hasAccessToAdministration')),
            new \Twig_SimpleFunction('list_administrations', array($this, 'listAdministrations')),
            new \Twig_SimpleFunction('current_administration', array($this, 'currentAdministration')),
            new \Twig_SimpleFunction('count_unprocessed_transactions', array($this, 'countUnprocessedTransactions')),
        );
    }

    public function hasAccessToAdministration(User $user, Administration $administration, $requiredPermission=Permission::EDIT)
    {
        return $this->accessManager->hasAccess($user, $administration, $requiredPermission);
    }

    public function listAdministrations(User $user) {
        return $this->administrationManager->listUserAdministrations($user);
    }

    public function currentAdministration(User $user) {
        return $this->administrationManager->getCurrentAdministration($user);
    }

    public function countUnprocessedTransactions(User $user) {
        $administration = $this->administrationManager->getCurrentAdministration($user);
        $transactions = $this->transactionManager->getUnProcessedTransactions($administration);
        return count($transactions);
    }

    public function getName()
    {
        return 'administration_extension';
    }
}