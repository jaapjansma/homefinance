<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use HomefinanceBundle\Administration\Filter\Filter;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Entity\Transaction;

class TransactionRepository extends EntityRepository {

    public function findByAdministration(Administration $administration) {
        return parent::findBy(array('administration' => $administration), array('date' => 'desc'));
    }

    public function findByAdministrationAndFilter(Administration $administration, Filter $filter) {
        $qb = $this->createQueryBuilder('transaction');
        $qb->where('transaction.administration = :administration');
        $qb->orderBy('transaction.date', 'DESC');
        $qb->setParameter(':administration', $administration);

        $filter->alterQueryBuilder($qb);

        return $qb->getQuery()->getResult();
    }

    public function findUnprocessedByAdministrationAndFilter(Administration $administration, Filter $filter) {
        $qb = $this->createQueryBuilder('transaction');
        $qb->where('transaction.administration = :administration');
        $qb->andWhere('transaction.is_processed = :processed');
        $qb->orderBy('transaction.date', 'DESC');
        $qb->setParameter(':administration', $administration);
        $qb->setParameter(':processed', false);

        $filter->alterQueryBuilder($qb);

        return $qb->getQuery()->getResult();
    }

    public function findByAdministrationAndYear(Administration $administration, $year) {
        $qb = $this->createQueryBuilder('transaction');
        $qb->where('transaction.administration = :administration');
        $qb->andWhere('YEAR(transaction.date) = ?1');
        $qb->orderBy('transaction.date', 'DESC');

        $qb->setParameter(1, $year);
        $qb->setParameter(':administration', $administration);

        return $qb->getQuery()->getResult();
    }

    public function findUnprocessedByAdministration(Administration $administration) {
        return parent::findBy(array('administration' => $administration, 'is_processed' => false), array('date' => 'desc'));
    }

    public function findOneByIdAndAdministration(Administration $administration, $id) {
        return parent::findOneBy(array(
            'administration' => $administration,
            'id' => $id,
        ));
    }

}