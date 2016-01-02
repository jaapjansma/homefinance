<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Rules;

use Doctrine\ORM\EntityManagerInterface;
use HomefinanceBundle\Entity\Rule;
use HomefinanceBundle\Entity\Transaction;

class Engine {

    /**
     * @var Factory
     */
    protected $factory;

    public function __construct(Factory $factory, EntityManagerInterface $entityManager)
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
    }

    public function triggerOnTransaction(Transaction $transaction) {
        $administration = $transaction->getAdministration();
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Rule');
        $rules = $repo->findByAdministration($administration);
        foreach($rules as $rule) {
            $this->triggerRule($transaction, $rule);
        }
    }

    public function triggerRule(Transaction $transaction, Rule $rule) {
        $areConditionValid = $this->areConditionsValid($transaction, $rule);
        if ($areConditionValid) {
            return $this->executeActions($transaction, $rule);
        }
        return false;
    }

    protected function executeActions(Transaction $transaction, Rule $rule) {
        $actions = 0;
        foreach($rule->getActions() as $action) {
            $actionClass = $this->factory->getActionClass($action->getAction());
            $actionClass->executeAction($transaction, $action);
            $actions++;
        }
        return $actions;
    }

    protected function areConditionsValid(Transaction $transaction, Rule $rule) {
        $isValid = true;
        $firstCondition = true;
        foreach($rule->getConditions() as $condition) {
            $conditionClass = $this->factory->getConditionClass($condition->getCondition());
            $conditionIsValid = $conditionClass->checkCondition($transaction, $condition);
            if ($firstCondition) {
                $isValid = $conditionIsValid ? true : false;
                $firstCondition = false;
            } elseif ($condition->getConditionLink() == 'OR') {
                if ($isValid || $conditionIsValid) {
                    $isValid = true;
                } else {
                    $isValid = false;
                }
            } else {
                if ($isValid && $conditionIsValid) {
                    $isValid = true;
                } else {
                    $isValid = false;
                }
            }
        }
        return $isValid;
    }

}