<?php

namespace HomefinanceBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Entity\BankAccount;

class BankAccountRepository extends EntityRepository {

    public function findByAdministration(Administration $administration) {
        return parent::findBy(array('administration' => $administration), array('iban' => 'asc'));
    }

    public function choices(Administration $administration) {
        $accounts = $this->findByAdministration($administration);
        return $accounts;
    }

    public function findOneByIdAndAdministration(Administration $administration, $id) {
        return parent::findOneBy(array(
            'administration' => $administration,
            'id' => $id,
        ));
    }

}