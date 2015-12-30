<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Rules;

use HomefinanceBundle\Entity\RuleCondition;
use HomefinanceBundle\Entity\Transaction;
use Symfony\Component\Form\Form;

interface ConditionInterface {

    /**
     * Checks whether this condition is true for the given transaction
     *
     * @param Transaction $transaction
     * @param RuleCondition $condition
     * @return bool
     */
    public function checkCondition(Transaction $transaction, RuleCondition $condition);

    /**
     * @param RuleCondition $condition
     * @return Form
     */
    public function getForm(RuleCondition $condition);

    /**
     * @param Form $form
     * @param RuleCondition $condition
     * @return void
     */
    public function setFormData(Form $form, RuleCondition $condition);

    /**
     * @param RuleCondition $condition
     * @return bool
     */
    public function hasForm(RuleCondition $condition);

    /**
     * @param Form $form
     * @param RuleCondition $condition
     * @return void
     */
    public function formIsSubmitted(Form $form, RuleCondition $condition);

    /**
     * @param RuleCondition $condition
     * @return string
     */
    public function getLabel(RuleCondition $condition);

    /**
     * @return string
     */
    public function getName();

}