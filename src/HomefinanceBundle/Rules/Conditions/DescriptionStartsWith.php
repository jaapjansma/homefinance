<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Rules\Conditions;

use HomefinanceBundle\Rules\ConditionInterface;
use HomefinanceBundle\Entity\RuleCondition;
use HomefinanceBundle\Entity\Transaction;
use HomefinanceBundle\Rules\Conditions\Form\DescriptionStartsWithForm;
use Symfony\Component\Form\Form;
use Symfony\Component\Translation\TranslatorInterface;

class DescriptionStartsWith implements ConditionInterface {

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Checks whether this condition is true for the given transaction
     *
     * @param Transaction $transaction
     * @param RuleCondition $condition
     * @return bool
     */
    public function checkCondition(Transaction $transaction, RuleCondition $condition) {
        $params = $this->getParams($condition);
        if (stripos(trim($transaction->getDescription()), $params['description']) === 0) {
            return true;
        }
        return false;
    }

    /**
     * @param RuleCondition $condition
     * @return Form
     */
    public function getForm(RuleCondition $condition) {
        return new DescriptionStartsWithForm();
    }

    public function setFormData(Form $form, RuleCondition $condition) {
        $params = $this->getParams($condition);
        $form->get('description')->setData($params['description']);
    }

    /**
     * @param Form $form
     * @param RuleCondition $condition
     * @return void
     */
    public function formIsSubmitted(Form $form, RuleCondition $condition) {
        $params = $this->getParams($condition);
        $params['description'] = $form->get('description')->getData();
        $condition->setRawParams($params);
    }

    /**
     * @param RuleCondition $condition
     * @return string
     */
    public function getLabel(RuleCondition $condition) {
        $params = $this->getParams($condition);
        return $this->translator->trans('rules.conditions.description_starts_with.label', array('%description%' => $params['description']), 'rules');
    }

    public function hasForm(RuleCondition $condition) {
        return true;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->translator->trans('rules.conditions.description_starts_with', array(), 'rules');
    }

    protected function getParams(RuleCondition $condition) {
        $params = $condition->getRawParams();
        if (empty($params) || !is_array($params)) {
            $params = array();
        }
        if (!isset($params['description'])) {
            $params['description'] = '';
        }
        return $params;
    }

}