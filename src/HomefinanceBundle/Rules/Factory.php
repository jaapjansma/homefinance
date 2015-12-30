<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Rules;

use HomefinanceBundle\Entity\RuleAction;
use HomefinanceBundle\Entity\RuleCondition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class Factory {

    protected $conditions;

    protected $actions;

    public function __construct()
    {
        $this->conditions = array();
        $this->actions = array();
    }

    public function addCondition(ConditionInterface $condition, $alias) {
        $this->conditions[$alias] = $condition;
    }

    public function addAction(ActionInterface $action, $alias) {
        $this->actions[$alias] = $action;
    }

    /**
     * @param $name
     * @return object
     * @throws ServiceNotFoundException
     */
    public function getConditionClass($name) {
        if (isset($this->conditions[$name])) {
            return $this->conditions[$name];
        }
        throw new ServiceNotFoundException($name);
    }

    public function getAllConditions() {
        return $this->conditions;
    }

    public function getAllActions() {
        return $this->actions;
    }

    public function hasConditionForm(RuleCondition $condition) {
        $class = $this->getConditionClass($condition->getCondition());
        return $class->hasForm($condition);
    }

    public function hasActionForm(RuleAction $action) {
        $class = $this->getActionClass($action->getAction());
        return $class->hasForm($action);
    }

    public function getConditionLabel(RuleCondition $condition) {
        $class = $this->getConditionClass($condition->getCondition());
        return $class->getLabel($condition);
    }

    public function getActionLabel(RuleAction $action) {
        $class = $this->getActionClass($action->getAction());
        return $class->getLabel($action);
    }


    /**
     * @param $name
     * @return object
     * @throws ServiceNotFoundException
     */
    public function getActionClass($name) {
        if (isset($this->actions[$name])) {
            return $this->actions[$name];
        }
        throw new ServiceNotFoundException($name);
    }

}