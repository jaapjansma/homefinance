<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Rules\Actions;

use HomefinanceBundle\Rules\ActionInterface;
use HomefinanceBundle\Entity\RuleAction;
use HomefinanceBundle\Entity\Transaction;
use Symfony\Component\Form\Form;
use Symfony\Component\Translation\TranslatorInterface;

class SetProcessed implements ActionInterface {

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Executes the action
     *
     * @param Transaction $transaction
     * @param RuleAction $action
     * @return bool
     */
    public function executeAction(Transaction $transaction, RuleAction $action) {
        $transaction->setProcessed(true);
    }

    /**
     * @param RuleAction $action
     * @return Form
     */
    public function getForm(RuleAction $action) {
        return null;
    }

    public function setFormData(Form $form, RuleAction $action) {
    }


    /**
     * @param Form $form
     * @param RuleAction $action
     * @return void
     */
    public function formIsSubmitted(Form $form, RuleAction $action) {
    }

    /**
     * @param RuleAction $action
     * @return string
     */
    public function getLabel(RuleAction $action) {
        return $this->translator->trans('rules.actions.set_processed.label', array(), 'rules');
    }

    public function hasForm(RuleAction $action) {
        return false;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->translator->trans('rules.actions.set_processed.name', array(), 'rules');
    }

}