<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Administration\Manager;

use HomefinanceBundle\Entity\Administration;
use Doctrine\ORM\EntityManager;
use HomefinanceBundle\Entity\Transaction;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransactionManager
{

    /**
     * @var EntityManager;
     */
    protected $entityManager;


    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUnProcessedTransactions(Administration $administration) {
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Transaction');
        $transactions = $repo->findUnprocessedByAdministration($administration);
        return $transactions;
    }

    public function getNextUnprocessedTransaction(Administration $administration, Transaction $transaction=null) {
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Transaction');
        $transactions = $repo->findUnprocessedByAdministration($administration);
        $found = false;
        if ($transaction != null) {
            foreach ($transactions as $t) {
                if ($found) {
                    return $t;
                }
                if ($t->getId() == $transaction->getId()) {
                    $found = true;
                }
            }
        }

        if (count($transactions)) {
            return reset($transactions);
        }

        return null;
    }

    public function getDistinctYears(Administration $administration) {
        $dql = "SELECT DISTINCT YEAR(transaction.date) as year FROM HomefinanceBundle\Entity\Transaction transaction WHERE transaction.administration = :administration";
        $query = $this->entityManager->createQuery($dql);
        $query->setParameter(':administration', $administration);
        $return = array();
        foreach($query->getResult() as $row) {
            $return[$row['year']] = $row['year'];
        }

        $now = new \DateTime();
        $return[$now->format('Y')] = $now->format('Y');

        ksort($return);
        return $return;
    }

    public function getTransactionsGroupedByTag(Administration $administration, $year) {
        $dql = "SELECT SUM(transaction.amount) as total, MONTH(transaction.date) as month, tag.id as tag_id
                FROM HomefinanceBundle\Entity\Transaction transaction
                INNER JOIN transaction.tags tag
                WHERE YEAR(transaction.date) = ?1 AND transaction.administration = :administration
                GROUP BY tag.id, month
                ";

        $query = $this->entityManager->createQuery($dql);
        $query->setParameter(1, $year);
        $query->setParameter(':administration', $administration);

        return $query->getResult();
    }

    public function getTransactionsGroupedByCategory(Administration $administration, $year) {
        $dql = "SELECT SUM(transaction.amount) as total, MONTH(transaction.date) as month, category.id as category_id
                FROM HomefinanceBundle\Entity\Transaction transaction
                LEFT JOIN transaction.category category
                WHERE YEAR(transaction.date) = ?1 AND transaction.administration = :administration
                GROUP BY transaction.category, month
                ORDER BY category.lft, month";

        $query = $this->entityManager->createQuery($dql);
        $query->setParameter(1, $year);
        $query->setParameter(':administration', $administration);

        return $query->getResult();
    }
}