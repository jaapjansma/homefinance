<?php

namespace HomefinanceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Transaction
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HomefinanceBundle\Entity\Repository\TransactionRepository")
 */
class Transaction {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\Iban()
     * @ORM\Column(name="iban", type="string", length=255, nullable=true)
     */
    private $iban;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var Administration
     *
     * @ORM\ManyToOne(targetEntity="Administration")
     * @ORM\JoinColumn(name="administration_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $administration;

    /**
     * @var BankAccount
     *
     * @ORM\ManyToOne(targetEntity="BankAccount")
     * @ORM\JoinColumn(name="bank_account_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $bank_account;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $category;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="tag_transactions",
     *      joinColumns={@ORM\JoinColumn(name="transaction_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $tags;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="parent")
     **/
    private $children;

    /**
     * @var Transaction
     *
     * @ORM\ManyToOne(targetEntity="Transaction", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $parent = null;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_processed", type="boolean")
     */
    private $is_processed = false;

    /**
     * @var string
     *
     * @ORM\Column(name="source_data", type="text", nullable=true)
     */
    private $source_data;

    /**
     * @var string
     *
     * @ORM\Column(name="source_id", type="string", length=255, nullable=true)
     */
    private $source_id;

    public function __construct() {
        $this->tags = new ArrayCollection();
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
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param $description
     * @return Transaction
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param \DateTime $dateTime
     * @return Transaction
     */
    public function setDate(\DateTime $dateTime) {
        $this->date = $dateTime;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Transaction
     */
    public function setAmount($amount) {
        $this->amount = (float) $amount;
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
     * @return Transaction
     */
    public function setAdministration(Administration $administration) {
        $this->administration = $administration;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Transaction
     */
    public function setCategory(Category $category) {
        $this->category = $category;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * @param $tags
     * @return Transaction
     */
    public function setTags($tags) {
        $this->tags;
        return $this;
    }

    /**
     * @return Transaction
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param Transaction $parent
     * @return Transaction
     */
    public function setParent(Transaction $parent) {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * @param Transaction $child
     * @return Transaction
     */
    public function addChild(Transaction $child) {
        $this->children->add($child);
        return $this;
    }

    /**
     * @param Transaction $child
     * @return Transaction
     */
    public function removeChild(Transaction $child) {
        $this->children->removeElement($child);
        return $this;
    }

    /**
     * @param Tag $tag
     * @return Transaction
     */
    public function addTag(Tag $tag) {
        $this->tags->add($tag);
        return $this;
    }

    /**
     * @param Tag $tag
     * @return Transaction
     */
    public function removeTag(Tag $tag) {
        $this->tags->removeElement($tag);
        return $this;
    }

    /**
     * @param $name
     * @return Transaction
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    /**
     * @param $iban
     * @return Transaction
     */
    public function setIban($iban) {
        $this->iban = $iban;
        return $this;
    }

    /**
     * @return string
     */
    public function getIban() {
        return $this->iban;
    }

    /**
     * @return bool
     */
    public function isProcessed() {
        return $this->is_processed ? true : false;
    }

    /**
     * @param bool $is_processed
     * @return Transaction
     */
    public function setProcessed($is_processed) {
        $this->is_processed = $is_processed ? true : false;
        return $this;
    }

    /**
     * @param BankAccount $bankAccount
     * @return Transaction
     */
    public function setBankAccount(BankAccount $bankAccount) {
        $this->bank_account = $bankAccount;
        return $this;
    }

    /**
     * @return BankAccount
     */
    public function getBankAccount() {
        return $this->bank_account;
    }

    /**
     * @return string
     */
    public function getSourceData() {
        return $this->source_data;
    }

    /**
     * @param $data
     * @return Transaction
     */
    public function setSourceData($data) {
        $this->source_data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceId() {
        return $this->source_id;
    }

    /**
     * @param $source_id
     * @return Transaction
     */
    public function setSourceId($source_id) {
        $this->source_id = $source_id;
        return $this;
    }

}