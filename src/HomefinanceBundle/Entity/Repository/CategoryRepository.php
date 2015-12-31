<?php

namespace HomefinanceBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use HomefinanceBundle\Category\Builder;
use HomefinanceBundle\Category\Type;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Entity\Category;

class CategoryRepository extends NestedTreeRepository
{
    public function getCategoriesByAdministration(Administration $administration) {
        return parent::findBy(array('administration' => $administration));
    }

    public function findOneBySlugAndAdministration(Administration $administration, $slug) {
        return parent::findOneBy(array(
            'administration' => $administration,
            'slug' => $slug,
        ));
    }

    public function getRootByAdministration(Administration $administration) {
        $meta = $this->getClassMetadata();
        $config = $this->listener->getConfiguration($this->_em, $meta->name);

        $qb = $this->getQueryBuilder();
        $qb->select('node')->from($config['useObjectClass'], 'node');
        $qb->where($qb->expr()->eq('node.id', 'node.root'));
        $qb->andWhere('node.level = 0');
        $qb->andWhere('node.administration = :administration');
        $qb->setParameter('administration', $administration);

        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();
        if ($result) {
            return $result;
        }

        //root doesn't exist create one
        $builder = new Builder();
        $rootCategory = $builder->createRootCategory($administration);
        $em = $this->getEntityManager();
        $em->persist($rootCategory);
        $em->flush();
        return $rootCategory;
    }

    public function getChildrenByAdministration(Administration $administration, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
        $root = $this->getRootByAdministration($administration);
        return $this->getChildren($root, $direct, $sortByField, $direction, $includeNode);
    }

    public function getChildrenQueryBuilderByAdministration(Administration $administration, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = true) {
        $root = $this->getRootByAdministration($administration);
        return parent::getChildrenQueryBuilder($root, $direct, $sortByField, $direction, $includeNode);
    }


    public function childrenHierachyByAdministration(Administration $administration, $direct = false, array $options = array()) {
        $root = $this->getRootByAdministration($administration);
        return $this->childrenHierarchy($root, $direct, $options);
    }


}