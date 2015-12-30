<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Entity\Rule;

class RuleRepository extends EntityRepository {

    public function findByAdministration(Administration $administration) {
        return parent::findBy(array('administration' => $administration));
    }

    public function findOneByIdAndAdministration(Administration $administration, $id) {
        return parent::findOneBy(array(
            'administration' => $administration,
            'id' => $id,
        ));
    }

}