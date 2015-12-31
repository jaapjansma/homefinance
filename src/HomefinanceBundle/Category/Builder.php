<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Category;

use HomefinanceBundle\Entity\Category;
use HomefinanceBundle\Entity\Administration;

class Builder {

    public function addChild(Category $parent, $title, $type) {
        $category = new Category();
        $category->setAdministration($parent->getAdministration());
        $category->setTitle($title);
        $category->setType($type);
        $category->setParent($parent);
        $parent->getChildren()->add($category);

        return $category;
    }

    public function createRootCategory(Administration $administration) {
        $rootCategory = new Category();
        $rootCategory->setAdministration($administration);
        $rootCategory->setTitle('category.root');
        $rootCategory->setType(Type::ROOT);
        return $rootCategory;
    }

}