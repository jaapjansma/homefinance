<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Rules;

use HomefinanceBundle\Entity\RuleAction;
use HomefinanceBundle\Entity\Transaction;
use Symfony\Component\Form\Form;

interface ActionInterface {

    /**
     * Executes the action
     *
     * @param Transaction $transaction
     * @param RuleAction $action
     * @return bool
     */
    public function executeAction(Transaction $transaction, RuleAction $action);

    /**
     * @param RuleAction $action
     * @return Form
     */
    public function getForm(RuleAction $action);

    /**
     * @param Form $form
     * @param RuleAction $action
     * @return void
     */
    public function setFormData(Form $form, RuleAction $action);

    /**
     * @param Form $form
     * @param RuleAction $action
     * @return void
     */
    public function formIsSubmitted(Form $form, RuleAction $action);

    /**
     * @param RuleAction $action
     * @return bool
     */
    public function hasForm(RuleAction $action);

    /**
     * @param RuleAction $action
     * @return string
     */
    public function getLabel(RuleAction $action);

    /**
     * @return string
     */
    public function getName();

}