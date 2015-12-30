<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Importer\Importers;

use HomefinanceBundle\Administration\Manager\BankAccountManager;
use HomefinanceBundle\Entity\Transaction;
use HomefinanceBundle\Importer\Exception\InvalidBankAccountException;
use HomefinanceBundle\Importer\Exception\InvalidCurrencyException;
use HomefinanceBundle\Importer\ImporterInterface;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Entity\BankAccount;
use Symfony\Component\HttpFoundation\File\File;

class AsnBankCsv implements ImporterInterface {

    /**
     * @var BankAccountManager
     */
    protected $bankAccountManager;

    public function __construct(BankAccountManager $bankAccountManager)
    {
        $this->bankAccountManager = $bankAccountManager;
    }

    public function getName() {
        return 'importers.asnbankcsv';
    }

    public function skipBankAccountCheck() {
        return true;
    }

    /**
     * @param File $file
     * @param Administration $administration
     * @return array
     *
     * @throws InvalidBankAccountException
     * @throws InvalidCurrencyException
     */
    public function import(File $file, Administration $administration)
    {
        $return = array();
        if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
            while(($row = fgetcsv($handle)) !== FALSE) {
                $return[] = $this->createTransactionFromRow($row, $administration);
            }
            fclose($handle);
        }
        return $return;
    }

    protected function createTransactionFromRow($row, Administration $administration) {
        $transaction = new Transaction();
        $transaction->setAdministration($administration);


        $bankAccount = $this->bankAccountManager->findBankAccount($row[1], $administration);
        if (!$bankAccount || $row[1] != $bankAccount->getIban()) {
            throw new InvalidBankAccountException();
        }
        $transaction->setBankAccount($bankAccount);

        if ($row[9] != 'EUR') {
            throw new InvalidCurrencyException();
        }
        $transaction->setDate(new \DateTime($row[0])); //boekdatum
        $amount = (float) $row[10];

        $transaction->setAmount($amount);

        $transaction->setIban($row[2]);
        $transaction->setName($row[3]);

        $description = $row[16]."\r\n".$row[17];
        $description = trim($description);
        $transaction->setDescription($description);
        if (empty($transaction->getName())) {
            $transaction->setName(strtok($description, "\r\n"));
        }

        $transaction->setSourceData(json_encode($row));
        $transaction->setSourceId(md5($row[11].$row[15]));
        return $transaction;
    }

}