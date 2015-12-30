<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Administration\Controller;

use HomefinanceBundle\DefaultController\DefaultController;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Entity\Transaction;
use HomefinanceBundle\Filters\Model\FilterData;
use HomefinanceBundle\Share\Permission;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TransactionController extends DefaultController {

    /**
     * @Route("/transactions/unprocessed", name="list_unprocessed_transactions")
     * @param Request $request
     * @return string
     */
    public function listUnprocessedAction(Request $request) {
        $administration = $this->checkCurrentAdministration(Permission::VIEW);
        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Transaction');

        $transactions = $repo->findUnprocessedByAdministration($administration);

        return $this->render('HomefinanceBundle:Transaction:list.html.twig', array(
            'transactions' => $transactions,
            'administration' => $administration,
            'title' => $this->get('translator')->trans('transactions.list.unprocessed.title', array(), 'administration'),
        ));
    }

    /**
     * @Route("/transactions/all/{year}", defaults={"year"=null}, name="list_all_transactions")
     * @param Request $request
     * @return string
     */
    public function listAllTransactions(Request $request, $year=null) {
        $administration = $this->checkCurrentAdministration(Permission::VIEW);
        if ($year === null) {
            $now = new \DateTime();
            $year = $now->format('Y');
        }
        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Transaction');
        $transactionManager = $this->get('homefinance.transaction.manager');

        $transactions = $repo->findByAdministrationAndYear($administration, $year);

        return $this->render('HomefinanceBundle:Transaction:list.html.twig', array(
            'transactions' => $transactions,
            'administration' => $administration,
            'title' => $this->get('translator')->trans('transactions.list.all.title', array(), 'administration'),
            'years' => $transactionManager->getDistinctYears($administration),
            'year' => $year
        ));
    }

    /**
     * @Route("/transactions/by_tag/{year}", defaults={"year"=null}, name="transactions_by_tag")
     * @param Request $request
     * @return string
     */
    public function overviewByTag(Request $request, $year=null) {
        $administration = $this->checkCurrentAdministration(Permission::VIEW);
        if ($year === null) {
            $now = new \DateTime();
            $year = $now->format('Y');
        }

        $dateUtils = $this->get('homefinance.date_utils');
        $transactionManager = $this->get('homefinance.transaction.manager');
        $result = $transactionManager->getTransactionsGroupedByTag($administration, $year);
        $months = $dateUtils->getMonths();

        $tags = array();
        $allTags = $this->getDoctrine()->getRepository('HomefinanceBundle:Tag')->findByAdministration($administration);
        foreach($allTags as $t) {
            $tags[$t->getId()] = $t->getName();
        }

        $pivotTable = array();
        foreach($result as $row) {
            $tag_id = $row['tag_id'];
            if ($tag_id) {
                $pivotTable[$tag_id][$row['month']] = $row['total'];
            }
        }

        return $this->render('HomefinanceBundle:Transaction:by_tag.html.twig', array(
            'pivotTable' => $pivotTable,
            'columns' => $months,
            'rows' => $tags,
            'years' => $transactionManager->getDistinctYears($administration),
            'year' => $year
        ));
    }

    /**
     * @Route("/transactions/by_category/{year}", defaults={"year"=null}, name="transactions_by_category")
     * @param Request $request
     * @return string
     */
    public function overviewByCategory(Request $request, $year=null) {
        $administration = $this->checkCurrentAdministration(Permission::VIEW);
        if ($year === null) {
            $now = new \DateTime();
            $year = $now->format('Y');
        }

        $dateUtils = $this->get('homefinance.date_utils');
        $transactionManager = $this->get('homefinance.transaction.manager');
        $result = $transactionManager->getTransactionsGroupedByCategory($administration, $year);
        $months = $dateUtils->getMonths();

        $categories = array();
        $cats = $this->getDoctrine()->getRepository('HomefinanceBundle:Category')->getChildrenByAdministration($administration, false);
        foreach($cats as $cat) {
            $categories[$cat->getId()] = $cat;
        }
        $categories[0] = $this->get('translator')->trans('transactions.no_category', array(), 'administration');

        $pivotTable = array();
        foreach($result as $row) {
            $cat_id = $row['category_id'];
            if (!$cat_id) {
                $cat_id = 0;
            }
            $pivotTable[$cat_id][$row['month']] = $row['total'];
        }

        return $this->render('HomefinanceBundle:Transaction:by_category.html.twig', array(
            'pivotTable' => $pivotTable,
            'columns' => $months,
            'rows' => $categories,
            'years' => $transactionManager->getDistinctYears($administration),
            'year' => $year
        ));
    }

    /**
     * @Route("/transactions/import", name="transaction_import")
     * @param Request $request
     * @return string
     */
    public function importTransactions(Request $request) {
        $administration = $this->checkCurrentAdministration(Permission::EDIT);

        $factory = $this->get('homefinance.importer.factory');
        $form = $this->createFormBuilder(null, array('translation_domain' => 'administration'))
            ->add('importer', 'choice', array('label' => 'transaction.import.importer', 'choices' => $factory->getImporterChoices()))
            ->add('import_file', 'file', array('label' => 'transaction.import.file'))
            ->add('save', 'submit', array('label' => 'transaction.import.btn-label',  'attr' => array('class' => 'btn btn-lg btn-success')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $importer = $factory->getImporter($form->get('importer')->getData());
            $file = $form->get('import_file')->getData();
            $transactions = $importer->import($file, $administration);

            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('HomefinanceBundle:Transaction');
            $engine = $this->get('homefinance.rules.engine');
            $count = 0;
            $skipped = 0;
            foreach($transactions as $transaction) {
                $duplicateTransaction = $repo->findOneBY(array(
                    'administration' => $administration,
                    'source_id' => $transaction->getSourceId()
                ));
                if (!$duplicateTransaction) {
                    $engine->triggerOnTransaction($transaction);
                    $em->persist($transaction);
                    $count ++;
                } else {
                    $skipped++;
                }
            }
            $em->flush();

            $trans = $this->get('translator');
            if ($skipped) {
                $this->addFlash('danger', $trans->trans('transaction.import.skipped', array('%count%' => $count, '%skipped%' => $skipped)));
            }
            $this->addFlash(($count > 0 ? 'success' : 'info'), $trans->trans('transaction.import.count', array('%count%' => $count, '%skipped%' => $skipped)));
            $this->addFlash('success', $trans->trans('transaction.import.success', array('%count%' => $count, '%skipped%' => $skipped)));

            return $this->redirect($this->generateUrl('list_unprocessed_transactions'));
        }

        return $this->render('HomefinanceBundle:Transaction:import.html.twig', array(
            'form' => $form->createView(),
            'administration' => $administration,
        ));
    }

    /**
     * @Route("/transactions/{id}/edit", name="transaction_edit")
     *
     * @param Request $request
     * @param $id
     * @return string
     */
    public function editTransactionAction(Request $request, $id) {
        $administration = $this->checkCurrentAdministration(Permission::EDIT);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HomefinanceBundle:Transaction');
        $transaction = $repo->findOneByIdAndAdministration($administration, $id);

        return $this->edit($transaction, $request);
    }

    /**
     * @Route("/transactions/new", name="transaction_new")
     * @param Request $request
     * @return string
     */
    public function newAction(Request $request) {
        $administration = $this->checkCurrentAdministration(Permission::EDIT);
        $transaction = new Transaction();
        $transaction->setAdministration($administration);
        $transaction->setDate(new \DateTime());

        return $this->edit($transaction, $request);
    }

    protected function edit(Transaction $transaction, Request $request) {
        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:BankAccount');
        $bank_accounts = $repo->choices($transaction->getAdministration());
        $transactionManager = $this->get('homefinance.transaction.manager');

        $tagManager = $this->get('homefinance.tag.manager');
        $tags = $tagManager->listAllTags();

        $categoryManager = $this->get('homefinance.category.manager');
        $categories = $categoryManager->allLeafCategories();

        $form = $this->createForm('transaction', $transaction, array(
            'bank_accounts' => $bank_accounts,
            'categories' => $categories,
        ));

        if ($transaction->getId() && !$transaction->isProcessed()) {
            $form->add('save_process_next', 'submit', array(
                'label' => 'transaction.save_process_next.btn-label',
                'attr' => array(
                    'class' => 'btn btn-lg btn-success',
                ),
            ));
        }

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $nextTransaction = null;
            if ($form->get('save_process_next')->isClicked()) {
                $nextTransaction = $transactionManager->getNextUnprocessedTransaction($transaction->getAdministration(), $transaction);
            }

            $transaction->setProcessed(true);

            $engine = $this->get('homefinance.rules.engine');
            $engine->triggerOnTransaction($transaction);

            $em->persist($transaction);
            $em->flush();

            $this->addFlash('success', 'transaction.updated');

            if ($nextTransaction != null) {
                return $this->redirect($this->generateUrl('transaction_edit', array('id' => $nextTransaction->getId())));
            }

            return $this->redirect($this->generateUrl('list_unprocessed_transactions'));
        }

        return $this->render('HomefinanceBundle:Transaction:edit.html.twig', array(
            'form' => $form->createView(),
            'tags' => $tags,
            'transaction' => $transaction,
        ));
    }

}