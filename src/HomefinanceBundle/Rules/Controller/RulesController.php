<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Rules\Controller;

use HomefinanceBundle\DefaultController\DefaultController;
use HomefinanceBundle\Entity\Rule;
use HomefinanceBundle\Entity\RuleAction;
use HomefinanceBundle\Entity\RuleCondition;
use HomefinanceBundle\Share\Permission;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class RulesController extends DefaultController
{

    /**
     * @Route("/", name="rules")
     * @param Request $request
     * @return string
     */
    public function listRules(Request $request)
    {
        $administration = $this->checkCurrentAdministration(Permission::EDIT);

        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Rule');
        $rules = $repo->findByAdministration($administration);

        $factory = $this->get('homefinance.rules.factory');

        return $this->render('HomefinanceBundle:Rules:rules.html.twig', array(
            'administration' => $administration,
            'rules' => $rules,
            'factory' => $factory
        ));
    }

    /**
     * @Route("/add", name="add_rule")
     * @param Request $request
     * @return string
     */
    public function addRule(Request $request)
    {
        $administration = $this->checkCurrentAdministration(Permission::EDIT);

        $rule = new Rule();
        $rule->setAdministration($administration);
        $em = $this->getDoctrine()->getManager();
        $em->persist($rule);
        $em->flush();
        $this->addFlash('success', 'rule.added');

        return $this->redirect($this->generateUrl('rules'));
    }

    /**
     * @Route("/{rule_id}/delete", name="delete_rule")
     *
     * @param Request $request
     * @param $rule_id
     * @return Response
     */
    public function deleteRule(Request $request, $rule_id) {
        $factory = $this->get('homefinance.rules.factory');
        $administration = $this->checkCurrentAdministration(Permission::EDIT);
        $rule_repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Rule');
        $rule = $rule_repo->findOneByIdAndAdministration($administration, $rule_id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($rule);
        $em->flush();
        $this->addFlash('success', 'rule.deleted');

        return $this->redirect($this->generateUrl('rules'));
    }

    /**
     * @Route("/{rule_id}/run", name="run_rule")
     *
     * @param Request $request
     * @param $rule_id
     * @return Response
     */
    public function runRules(Request $request, $rule_id) {
        $em = $this->getDoctrine()->getManager();
        $engine = $this->get('homefinance.rules.engine');
        $administration = $this->checkCurrentAdministration(Permission::VIEW);
        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Transaction');

        $transactions = $repo->findUnprocessedByAdministration($administration);

        $rule_repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Rule');
        $rule = $rule_repo->findOneByIdAndAdministration($administration, $rule_id);

        $transactions_processed = 0;
        foreach($transactions as $transaction) {
            if ($engine->triggerRule($transaction, $rule)) {
                $em->persist($transaction);
                $transactions_processed++;
            }
        }

        $this->addFlash('success', $this->get('translator')->trans('rule.executed', array('%count%' => $transactions_processed)));
        $em->flush();
        return $this->redirect($this->generateUrl('rules'));
    }

    /**
     * @Route("/{rule_id}/add_condition", name="add_condition")
     *
     * @param Request $request
     * @param $rule_id
     * @return Response
     */
    public function addCondition(Request $request, $rule_id) {
        $factory = $this->get('homefinance.rules.factory');
        $administration = $this->checkCurrentAdministration(Permission::EDIT);
        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Rule');
        $rule = $repo->findOneByIdAndAdministration($administration, $rule_id);
        $condition = new RuleCondition();
        $condition->setRule($rule);

        $form = $this->createForm('rules_condition', $condition);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($condition);
            $em->flush();
            $this->addFlash('success', 'condition.edited');

            if ($factory->hasConditionForm($condition)) {
                return $this->redirect($this->generateUrl('edit_condition', array(
                    'rule_id' => $rule_id,
                    'condition_id' => $condition->getId(),
                )));
            }
            return $this->redirect($this->generateUrl('rules'));
        }

        return $this->render('HomefinanceBundle:Rules:add_condition.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{rule_id}/delete_condition/{condition_id}", name="delete_condition")
     *
     * @param Request $request
     * @param $rule_id
     * @param $condition_id
     * @return Response
     */
    public function deleteCondition(Request $request, $rule_id, $condition_id) {
        $factory = $this->get('homefinance.rules.factory');
        $administration = $this->checkCurrentAdministration(Permission::EDIT);
        $rule_repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Rule');
        $rule = $rule_repo->findOneByIdAndAdministration($administration, $rule_id);

        $condition_repo = $this->getDoctrine()->getRepository('HomefinanceBundle:RuleCondition');
        $condition = $condition_repo->findOneBy(array('id' => $condition_id));

        $em = $this->getDoctrine()->getManager();
        $em->remove($condition);
        $em->flush();
        $this->addFlash('success', 'condition.deleted');

        return $this->redirect($this->generateUrl('rules'));
    }

    /**
     * @Route("/{rule_id}/edit_condition/{condition_id}", name="edit_condition")
     *
     * @param Request $request
     * @param $rule_id
     * @param $condition_id
     * @return Response
     */
    public function editCondition(Request $request, $rule_id, $condition_id) {
        $factory = $this->get('homefinance.rules.factory');
        $administration = $this->checkCurrentAdministration(Permission::EDIT);
        $rule_repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Rule');
        $rule = $rule_repo->findOneByIdAndAdministration($administration, $rule_id);

        $condition_repo = $this->getDoctrine()->getRepository('HomefinanceBundle:RuleCondition');
        $condition = $condition_repo->findOneBy(array('id' => $condition_id));
        $class = $factory->getConditionClass($condition->getCondition());
        $form = $this->createForm($class->getForm($condition), $condition);
        $class->setFormData($form, $condition);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $class->formIsSubmitted($form, $condition);
            $em = $this->getDoctrine()->getManager();
            $em->persist($condition);
            $em->flush();
            $this->addFlash('success', 'condition.edited');

            return $this->redirect($this->generateUrl('rules'));
        }

        return $this->render('HomefinanceBundle:Rules:edit_condition.html.twig', array(
            'form' => $form->createView(),
            'factory' => $factory,
            'condition' => $condition,
        ));
    }

    /**
     * @Route("/{rule_id}/add_action", name="add_action")
     *
     * @param Request $request
     * @param $rule_id
     * @return Response
     */
    public function addAction(Request $request, $rule_id) {
        $factory = $this->get('homefinance.rules.factory');
        $administration = $this->checkCurrentAdministration(Permission::EDIT);
        $repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Rule');
        $rule = $repo->findOneByIdAndAdministration($administration, $rule_id);
        $action = new RuleAction();
        $action->setRule($rule);

        $form = $this->createForm('rules_action', $action);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();
            $this->addFlash('success', 'action.edited');

            if ($factory->hasActionForm($action)) {
                return $this->redirect($this->generateUrl('edit_action', array(
                    'rule_id' => $rule_id,
                    'action_id' => $action->getId(),
                )));
            }
            return $this->redirect($this->generateUrl('rules'));
        }

        return $this->render('HomefinanceBundle:Rules:add_action.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{rule_id}/edit_action/{action_id}", name="edit_action")
     *
     * @param Request $request
     * @param $rule_id
     * @param $action_id
     * @return Response
     */
    public function editAction(Request $request, $rule_id, $action_id) {
        $factory = $this->get('homefinance.rules.factory');
        $administration = $this->checkCurrentAdministration(Permission::EDIT);
        $rule_repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Rule');
        $rule = $rule_repo->findOneByIdAndAdministration($administration, $rule_id);

        $action_repo = $this->getDoctrine()->getRepository('HomefinanceBundle:RuleAction');
        $action = $action_repo->findOneBy(array('id' => $action_id));
        $class = $factory->getActionClass($action->getAction());
        $form = $this->createForm($class->getForm($action), $action);
        $class->setFormData($form, $action);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $class->formIsSubmitted($form, $action);
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();
            $this->addFlash('success', 'action.edited');

            return $this->redirect($this->generateUrl('rules'));
        }

        return $this->render('HomefinanceBundle:Rules:edit_action.html.twig', array(
            'form' => $form->createView(),
            'factory' => $factory,
            'condition' => $action,
        ));
    }

    /**
     * @Route("/{rule_id}/delete_action/{action_id}", name="delete_action")
     *
     * @param Request $request
     * @param $rule_id
     * @param $action_id
     * @return Response
     */
    public function deleteAction(Request $request, $rule_id, $action_id) {
        $factory = $this->get('homefinance.rules.factory');
        $administration = $this->checkCurrentAdministration(Permission::EDIT);
        $rule_repo = $this->getDoctrine()->getRepository('HomefinanceBundle:Rule');
        $rule = $rule_repo->findOneByIdAndAdministration($administration, $rule_id);

        $action_repo = $this->getDoctrine()->getRepository('HomefinanceBundle:RuleAction');
        $action = $action_repo->findOneBy(array('id' => $action_id));

        $em = $this->getDoctrine()->getManager();
        $em->remove($action);
        $em->flush();
        $this->addFlash('success', 'action.deleted');

        return $this->redirect($this->generateUrl('rules'));
    }
}