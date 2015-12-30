<?php

namespace HomefinanceBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HomefinanceBundle\Entity\Repository\TagRepository")
 */
class Tag {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=64, unique=true)
     */
    private $slug;

    /**
     * @var Administration
     *
     * @ORM\ManyToOne(targetEntity="Administration")
     * @ORM\JoinColumn(name="administration_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $administration;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param $name
     * @return Tag
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
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

}