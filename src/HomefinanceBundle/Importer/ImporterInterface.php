<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Importer;

use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Entity\BankAccount;
use HomefinanceBundle\Entity\Transaction;
use Symfony\Component\HttpFoundation\File\File;

interface ImporterInterface {

    /**
     * @return string
     */
    public function getName();

    /**
     * @param File $file
     * @param Administration $administration
     * @return Transaction[]
     */
    public function import(File $file, Administration $administration);

    /**
     * @return bool
     */
    public function skipBankAccountCheck();
}