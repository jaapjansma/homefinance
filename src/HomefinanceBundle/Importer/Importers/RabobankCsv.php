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


        $bankAccount = $this->bankAccountManager->findBankAccount($row[0], $administration);
        if (!$bankAccount || $row[0] != $bankAccount->getIban()) {
            throw new InvalidBankAccountException();
        }
        $transaction->setBankAccount($bankAccount);

        if ($row[1] != 'EUR') {
            throw new InvalidCurrencyException();
        }
        $transaction->setDate(new \DateTime($row[7])); //boekdatum
        $amount = (float) $row[4];
        if ($row[3] == 'D') {
            $amount = (float) ($amount * -1.00);
        }
        $transaction->setAmount($amount);

        $transaction->setIban($row[5]);
        $transaction->setName($row[6]);

        $description = $row[10]."\r\n".$row[11]."\r\n".$row[12]."\r\n".$row[13]."\r\n".$row[14]."\r\n".$row[15]."\r\n".$row[16]."\r\n".$row[17]."\r\n".$row[18];
        $description = trim($description);
        $transaction->setDescription($description);
        if (empty($transaction->getName())) {
            $transaction->setName(strtok($description, "\r\n"));
        }

        $transaction->setSourceData(json_encode($row));
        $transaction->setSourceId(md5(base64_encode(json_encode($row))));
        return $transaction;
    }

}