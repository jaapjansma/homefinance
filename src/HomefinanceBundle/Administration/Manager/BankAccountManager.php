<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */


namespace HomefinanceBundle\Administration\Manager;

use HomefinanceBundle\Entity\Administration;
use Doctrine\ORM\EntityManager;
use HomefinanceBundle\Entity\BankAccount;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BankAccountManager
{

    /**
     * @var EntityManager;
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findBankAccount($iban, Administration $administration) {
        $repo = $this->entityManager->getRepository('HomefinanceBundle:BankAccount');
        $bankAccount = $repo->findOneBy(array(
            'administration' => $administration,
            'iban' => $iban
        ));
        return $bankAccount;
    }

    public function getSaldo(BankAccount $bankAccount) {
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Transaction');
        $transactions = $repo->findBy(array(
            'administration' => $bankAccount->getAdministration(),
            'bank_account' => $bankAccount,
        ));
        $total = (float) $bankAccount->getStartingBalance();
        foreach($transactions as $t) {
            $total = $total + $t->getAmount();
        }
        return $total;
    }
}
