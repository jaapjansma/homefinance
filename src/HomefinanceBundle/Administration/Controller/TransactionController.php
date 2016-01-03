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
     * @Route("/transactions/unprocessed{filter}/{value}", defaults={"filter"=null,"value"=null}, name="list_unprocessed_transactions")
     * @param Request $request
     * @return string
     */
    public function listUnprocessedAction(Request $request,$filter=null, $value=null) {
        $administration = $this->checkCurrentAdministration(Permission::VIEW);
        $filterFactory = $this->get('homefinance.filter_factory');
        $filterBag = $this->get('homefinance.filter_bag');
        $filterObject = $filterBag->get('unprocessed_transactions');
        if ($filter !== null) {
            $filterObject->set($filter, $value);
        }
        $filterObject->set('year', null);
        $filterBag->set('unprocessed_transactions', $filterObject);
        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Transaction');

        $transactions = $repo->findUnprocessedByAdministrationAndFilter($administration, $filterObject);

        return $this->render('HomefinanceBundle:Transaction:list.html.twig', array(
            'transactions' => $transactions,
            'administration' => $administration,
            'title' => $this->get('translator')->trans('transactions.list.unprocessed.title', array(), 'administration'),
            'filter' => $filterObject,
            'filterFactory' => $filterFactory,
            'showProcessedFilter' => false,
            'page' => 'list_unprocessed_transactions',
            'yearFilter' => false,
        ));
    }

    /**
     * @Route("/transactions/all/{filter}/{value}/{filter2}/{value2}", defaults={"filter"=null,"value"=null, "filter2"=null,"value2"=null}, name="list_all_transactions")
     * @param Request $request
     * @param $filter
     * @param $value
     * @param $filter2
     * @param $value2
     * @return string
     */
    public function listAllTransactions(Request $request, $filter=null, $value=null,$filter2=null,$value2=null) {
        $administration = $this->checkCurrentAdministration(Permission::VIEW);
        $filterFactory = $this->get('homefinance.filter_factory');
        $filterBag = $this->get('homefinance.filter_bag');
        $filterObject = $filterBag->get('all_transactions');
        if ($filter !== null) {
            $filterObject->set($filter, $value);
        }
        if ($filter2 != null) {
            $filterObject->set($filter2, $value2);
        }
        $filterBag->set('all_transactions', $filterObject);

        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Transaction');
        $transactions = $repo->findByAdministrationAndFilter($administration, $filterObject);

        return $this->render('HomefinanceBundle:Transaction:list.html.twig', array(
            'transactions' => $transactions,
            'administration' => $administration,
            'title' => $this->get('translator')->trans('transactions.list.all.title', array(), 'administration'),
            'page' => 'list_all_transactions',
            'filter' => $filterObject,
            'filterFactory' => $filterFactory,
            'showProcessedFilter' => true,
            'yearFilter' => true,
        ));
    }

    /**
     * @Route("/transactions/all_by_tag/{filter}/{value}/{filter2}/{value2}/{reset}", defaults={"filter"=null,"value"=null, "filter2"=null,"value2"=null, "reset"=false}, name="list_all_transactions_by_tag")
     * @param Request $request
     * @param $filter
     * @param $value
     * @param $filter2
     * @param $value2
     * @return string
     */
    public function listAllTransactionsByTag(Request $request, $filter=null, $value=null,$filter2=null,$value2=null,$reset=false) {
        $administration = $this->checkCurrentAdministration(Permission::VIEW);
        $filterFactory = $this->get('homefinance.filter_factory');
        $filterBag = $this->get('homefinance.filter_bag');
        $filterObject = $filterBag->get('all_transactions_by_tag', $reset);
        if ($filter !== null) {
            $filterObject->set($filter, $value);
        }
        if ($filter2 != null) {
            $filterObject->set($filter2, $value2);
        }
        $filterBag->set('all_transactions', $filterObject);

        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Transaction');
        $transactions = $repo->findByAdministrationAndFilter($administration, $filterObject);

        return $this->render('HomefinanceBundle:Transaction:list.html.twig', array(
            'transactions' => $transactions,
            'administration' => $administration,
            'title' => $this->get('translator')->trans('transactions.list.all.title', array(), 'administration'),
            'page' => 'list_all_transactions_by_tag',
            'filter' => $filterObject,
            'filterFactory' => $filterFactory,
            'showProcessedFilter' => true,
            'yearFilter' => true,
        ));
    }

    /**
     * @Route("/transactions/all_by_category/{filter}/{value}/{filter2}/{value2}/{reset}", defaults={"filter"=null,"value"=null, "filter2"=null,"value2"=null, "reset"=false}, name="list_all_transactions_by_category")
     * @param Request $request
     * @param $filter
     * @param $value
     * @param $filter2
     * @param $value2
     * @return string
     */
    public function listAllTransactionsByCategory(Request $request, $filter=null, $value=null,$filter2=null,$value2=null,$reset=false) {
        $administration = $this->checkCurrentAdministration(Permission::VIEW);
        $filterFactory = $this->get('homefinance.filter_factory');
        $filterBag = $this->get('homefinance.filter_bag');
        $filterObject = $filterBag->get('all_transactions_by_category', $reset);
        if ($filter !== null) {
            $filterObject->set($filter, $value);
        }
        if ($filter2 != null) {
            $filterObject->set($filter2, $value2);
        }
        $filterBag->set('all_transactions', $filterObject);

        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Transaction');
        $transactions = $repo->findByAdministrationAndFilter($administration, $filterObject);

        return $this->render('HomefinanceBundle:Transaction:list.html.twig', array(
            'transactions' => $transactions,
            'administration' => $administration,
            'title' => $this->get('translator')->trans('transactions.list.all.title', array(), 'administration'),
            'page' => 'list_all_transactions_by_category',
            'filter' => $filterObject,
            'filterFactory' => $filterFactory,
            'showProcessedFilter' => true,
            'yearFilter' => true,
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

        $allTags = $this->getDoctrine()->getRepository('HomefinanceBundle:Tag')->findByAdministration($administration);

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
            'rows' => $allTags,
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
     * @Route("/transactions/{id}/edit/{redirect}", defaults={"redirect"=null}, name="transaction_edit")
     *
     * @param Request $request
     * @param $id
     * @param $redirect
     * @return string
     */
    public function editTransactionAction(Request $request, $id, $redirect=null) {
        $administration = $this->checkCurrentAdministration(Permission::EDIT);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('HomefinanceBundle:Transaction');
        $transaction = $repo->findOneByIdAndAdministration($administration, $id);

        return $this->edit($transaction, $request, $redirect);
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

    protected function edit(Transaction $transaction, Request $request,$redirect=nul) {
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
            $form->add('cancel_and_next', 'submit', array(
                'label' => 'transaction.cancel_and_next.btn-label',
                'attr' => array(
                    'class' => 'btn btn-lg btn-warning',
                ),
            ));
        }

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $nextTransaction = null;
            if ($form->has('save_process_next') && $form->get('save_process_next')->isClicked()) {
                $nextTransaction = $transactionManager->getNextUnprocessedTransaction($transaction->getAdministration(), $transaction);
            } elseif ($form->has('cancel_and_next') && $form->get('cancel_and_next')->isClicked()) {
                $nextTransaction = $transactionManager->getNextUnprocessedTransaction($transaction->getAdministration(), $transaction);
                return $this->redirect($this->generateUrl('transaction_edit', array('id' => $nextTransaction->getId())));
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

            if ($redirect) {
                return $this->redirect($this->generateUrl($redirect));
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