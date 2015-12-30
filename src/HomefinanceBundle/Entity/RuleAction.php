<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RuleAction
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class RuleAction
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Rule
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Rule", inversedBy="actions")
     * @ORM\JoinColumn(name="rule_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $rule;

    /**
     * @var string
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;

    /**
     * @var string
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    private $params;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param $id
     * @return RuleAction
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Rule
     */
    public function getRule() {
        return $this->rule;
    }

    /**
     * @param Rule $rule
     * @return RuleAction
     */
    public function setRule(Rule $rule) {
        $this->rule = $rule;
        return $this;
    }

    /**
     * @param $action
     * @return RuleAction
     */
    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @param $params
     * @return RuleAction
     */
    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRawParams() {
        return json_decode($this->params, true);
    }

    /**
     * @param $params
     * @return RuleAction
     */
    public function setRawParams($params) {
        $this->params = json_encode($params);
        return $this;
    }
}