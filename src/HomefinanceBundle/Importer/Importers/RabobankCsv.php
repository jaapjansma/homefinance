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

class RabobankCsv implements ImporterInterface {

    /**
     * @var BankAccountManager
     */
    protected $bankAccountManager;

    public function __construct(BankAccountManager $bankAccountManager)
    {
        $this->bankAccountManager = $bankAccountManager;
    }

    public function getName() {
        return 'importers.rabobankcsv';
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
            $rowNr = 0;
            while(($row = fgetcsv($handle)) !== FALSE) {
                if ($rowNr > 0) {
                    $return[] = $this->createTransactionFromRow($row, $administration);
                }
                $rowNr++;
            }
            fclose($handle);
        }
        return $return;
    }

    protected function createTransactionFromRow($row, Administration $administration) {
        $transaction = new Transaction();
        $transaction->setAdministration($administration);


        $bankAccount = $this->bankAccountManager->findBankAccount($row[0], $administration);
        if (!$bankAccount || $row[0] != $bankAccount->getIban()) {
            throw new InvalidBankAccountException();
        }
        $transaction->setBankAccount($bankAccount);

        if ($row[1] != 'EUR') {
            throw new InvalidCurrencyException();
        }
        $transaction->setDate(new \DateTime($row[4])); //datum

        $amount = (float) str_replace(",", ".", $row[6]);
        $transaction->setAmount($amount);

        $transaction->setIban($row[8]);
        $transaction->setName($row[9]);

        $description = $row[15]."\r\n".$row[16]."\r\n".$row[17]."\r\n".$row[18]."\r\n".$row[19]."\r\n".$row[20]."\r\n".$row[21]."\r\n".$row[22]."\r\n".$row[18];
        $description = preg_replace("/[\r\n]+/", "\r\n", $description);
        $description = trim($description);
        $transaction->setDescription($description);
        $transactionName = $transaction->getName();
        if (empty($transactionName)) {
            $transaction->setName(strtok($description, "\r\n"));
        }

        $transaction->setSourceData(json_encode($row));
        $transaction->setSourceId($row[3]);
        return $transaction;
    }

}