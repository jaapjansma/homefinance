<?php

namespace HomefinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BankAccount
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HomefinanceBundle\Entity\Repository\BankAccountRepository")
 */
class BankAccount
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
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Iban()
     * @ORM\Column(name="iban", type="string", length=255)
     */
    private $iban;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var Administration
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Administration")
     * @ORM\JoinColumn(name="administration_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $administration;

    /**
     * @var float
     *
     * @ORM\Column(name="starting_balance", type="float")
     */
    private $starting_balance;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return BankAccount
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set iban
     *
     * @param string $iban
     * @return BankAccount
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string 
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set image
     *
     * You should encode it as base64
     *
     * @param string $image
     * @return BankAccount
     */
    public function setImage($image)
    {
        if ( base64_encode(base64_decode($image, true)) === $image){
            $this->image = $image;
        } else {
            $this->image = base64_encode($image);
        }


        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
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

    /**
     * @return float
     */
    public function getStartingBalance() {
        return $this->starting_balance;
    }

    /**
     * @param float $starting_balance
     * @return $this
     */
    public function setStartingBalance($starting_balance) {
        $this->starting_balance = $starting_balance;
        return $this;
    }

    public function getLabel() {
        return $this->name .' ('.$this->iban.')';
    }
}
