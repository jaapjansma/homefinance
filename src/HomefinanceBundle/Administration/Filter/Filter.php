<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Administration\Filter;

use Doctrine\ORM\QueryBuilder;

class Filter {

    protected $year = null;

    protected $category = null;

    protected $month = null;

    protected $tag = null;

    protected $processed = null;

    public function __construct()
    {
        $now = new \DateTime();
        $this->year = $now->format('Y');
    }

    public function set($name, $value) {
        switch($name) {
            case 'year':
                $this->year = $value;
                break;
            case 'category':
                $this->category = $value;
                break;
            case 'tag':
                $this->tag = $value;
                break;
            case 'processed':
                $this->processed = $value;
                break;
            case 'month':
                $this->month = $value;
                break;
        }
    }

    public function get($name) {
        switch($name) {
            case 'year':
                return $this->year;
                break;
            case 'category':
                return $this->category;
                break;
            case 'tag':
                return $this->tag;
                break;
            case 'processed':
                return $this->processed;
                break;
            case 'month':
                return $this->month;
                break;
        }
        return null;
    }

    public function alterQueryBuilder(QueryBuilder $queryBuilder) {
        if ($this->year !== null) {
            $queryBuilder->andWhere('YEAR(transaction.date) = :year');
            $queryBuilder->setParameter(':year', $this->year);
        }
        if ($this->month && in_array($this->month, array(1,2,3,4,5,6,7,8,9,10,11,12))) {
            $queryBuilder->andWhere('MONTH(transaction.date) = :month');
            $queryBuilder->setParameter(':month', $this->month);
        }
        if ($this->processed != null && in_array($this->processed, array(0,1))) {
            $queryBuilder->andWhere('transaction.is_processed = :processed');
            $queryBuilder->setParameter(':processed', $this->processed ? true : false);
        }
        if ($this->tag != null) {
            $queryBuilder->leftJoin('transaction.tags', 'tag');
            $queryBuilder->andWhere('tag.slug = :tag');
            $queryBuilder->setParameter(':tag', $this->tag);
        }
        if ($this->category !== null) {
            if ($this->category == 'empty') {
                $queryBuilder->andWhere('transaction.category IS NULL');
            } else {
                $queryBuilder->leftJoin('transaction.category', 'category');
                $queryBuilder->leftJoin('category.parent', 'parent');
                $queryBuilder->andWhere('category.slug = :category or parent.slug = :category');
                $queryBuilder->setParameter(':category', $this->category);
            }
        }
        return $queryBuilder;
    }

}