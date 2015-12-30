<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rule
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HomefinanceBundle\Entity\Repository\RuleRepository")
 */
class Rule
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
     * @var Administration
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Administration")
     * @ORM\JoinColumn(name="administration_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $administration;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RuleCondition", mappedBy="rule")
     */
    protected $conditions;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="RuleAction", mappedBy="rule")
     */
    protected $actions;

    public function __construct()
    {
        $this->conditions = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param $id
     * @return Rule
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Administration
     */
    public function getAdministration() {
        return $this->administration;
    }

    /**
     * @param Administration $administration
     * @return Rule
     */
    public function setAdministration(Administration $administration) {
        $this->administration = $administration;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getConditions() {
        return $this->conditions;
    }

    /**
     * @param ArrayCollection $conditions
     * @return Rule
     */
    public function setConditions(ArrayCollection $conditions) {
        $this->conditions = $conditions;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * @param ArrayCollection $actions
     * @return Rule
     */
    public function setActions(ArrayCollection $actions) {
        $this->actions = $actions;
        return $this;
    }
}