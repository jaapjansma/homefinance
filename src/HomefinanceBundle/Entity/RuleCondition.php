<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RuleCondition
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class RuleCondition
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
     * @ORM\ManyToOne(targetEntity="Rule", inversedBy="conditions")
     * @ORM\JoinColumn(name="rule_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $rule;

    /**
     * @var string
     * @ORM\Column(name="`condition`", type="string", length=255)
     */
    private $condition;

    /**
     * @var string
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    private $params;

    /**
     * @var string
     * @ORM\Column(name="condition_link", type="string", length=255, nullable=true)
     */
    private $condition_link = "AND";

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param $id
     * @return RuleCondition
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
     * @return RuleCondition
     */
    public function setRule(Rule $rule) {
        $this->rule = $rule;
        return $this;
    }

    /**
     * @param $condition
     * @return RuleCondition
     */
    public function setCondition($condition) {
        $this->condition = $condition;
        return $this;
    }

    /**
     * @return string
     */
    public function getCondition() {
        return $this->condition;
    }

    /**
     * @return string
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * @param $params
     * @return RuleCondition
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
     * @return RuleCondition
     */
    public function setRawParams($params) {
        $this->params = json_encode($params);
        return $this;
    }

    /**
     * @return string
     */
    public function getConditionLink() {
        return $this->condition_link;
    }

    /**
     * @param $condition_link
     * @return RuleCondition
     */
    public function setConditionLink($condition_link) {
        $this->condition_link = $condition_link;
        return $this;
    }
}