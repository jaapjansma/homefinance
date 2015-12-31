<?php

namespace HomefinanceBundle\Entity;

use HomefinanceBundle\Category\Exception\InvalidCategoryTypeException;
use HomefinanceBundle\Category\Type;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HomefinanceBundle\Entity\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(length=64)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(length=64)
     */
    private $type;

    /**
     * @var Administration
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Administration")
     * @ORM\JoinColumn(name="administration_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $administration;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"type", "title"})
     * @ORM\Column(length=64, unique=true)
     */
    private $slug;

    /**
     * @var int
     *
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @var int
     *
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @var Category
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var int
     *
     * @Gedmo\TreeRoot
     * @ORM\Column(type="integer", nullable=true)
     */
    private $root;

    /**
     * @var int
     *
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $level;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", cascade={"persist"})
     */
    private $children;

    public function __construct() {
        $this->children = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    public function getIndentedTitle() {
        $title = '';
        if ($this->parent && $this->parent->getLevel() > 0) {
            $title = $this->parent->getIndentedTitle();
        }
        if (strlen($title)) {
            $title = '  '.$title.': ';
        }
        return $title.$this->title;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Cateory
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param $type
     * @return $this
     * @throws InvalidCategoryTypeException
     */
    public function setType($type) {
        if (!Type::isTypeValid($type)) {
            throw new InvalidCategoryTypeException();
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @param Category $parent
     * @return Category
     */
    public function setParent(Category $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return int
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return int
     */
    public function getLeft()
    {
        return $this->lft;
    }

    /**
     * @return int
     */
    public function getRight()
    {
        return $this->rgt;
    }

    /**
     * @return Administration
     */
    public function getAdministration() {
        return $this->administration;
    }

    /**
     * @param Administration $administration
     * @return Category
     */
    public function setAdministration(Administration $administration) {
        $this->administration = $administration;
        return $this;
    }

    public function isDirectSubOfRoot() {
        if ($this->parent) {
            if ($this->parent->getType() == Type::ROOT) {
                return true;
            }
        }
        return false;
    }

    public function isFirstSibling() {
        if (($this->getLeft() - 1) == $this->parent->getLeft()) {
            return true;
        }
        return false;
    }

    public function isLastSibling() {
        if (($this->getRight() +1) == $this->parent->getRight()) {
            return true;
        }
        return false;
    }

    public function __toString() {
        return $this->getIndentedTitle();
    }
}