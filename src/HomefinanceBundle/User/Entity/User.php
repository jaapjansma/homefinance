<?php

namespace HomefinanceBundle\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use HomefinanceBundle\Entity\Administration;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @MappedSuperclass
 */
abstract class User implements AdvancedUserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=32)
     */
    protected $salt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastlogin", type="datetime", nullable=true)
     */
    protected $lastlogin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmation_token", type="string", length=128, nullable=true)
     */
    protected $confirmationToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="confirmation_requested_at", type="datetime", nullable=true)
     */
    protected $confirmationRequestedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="confirmation_token_valid_till", type="datetime", nullable=true)
     */
    protected $confirmationTokenValidTill;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="new_email", type="string", length=255, nullable=true)
     */
    protected $newEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @var boolean
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    protected $locked;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    protected $avatar;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var Administration
     *
     * @ORM\ManyToOne(targetEntity="Administration")
     * @ORM\JoinColumn(name="current_administration_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    protected $current_administration;


    public function __construct() {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->enabled = false;
        $this->locked = false;
        $this->roles = array();
    }


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
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getNewEmail() {
        return $this->newEmail;
    }

    /**
     * @param $email
     * @return User
     */
    public function setNewEmail($email=null) {
        $this->newEmail = $email;
        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        if ($this->locked) {
            return false;
        }
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        if ($this->enabled) {
            return true;
        }
        return false;
    }

    /**
     * @param bool $enabled
     * @return User
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled ? true : false;
        return $this;
    }

    public function getRoles() {
        return explode(",", $this->roles);
    }

    public function setRoles($roles) {
        if (is_array($roles)) {
            $this->roles = implode(",", $roles);
        } else {
            $this->roles = $roles;
        }
    }

    /**
     * @return string
     */
    public function getSalt() {
        return $this->salt;
    }

    public function eraseCredentials() {
    }

    /**
     * @return \DateTime
     */
    public function getPasswordRequestedAt() {
        return $this->passwordRequestedAt;
    }

    /**
     * @param \DateTime $datetime
     * @return User
     */
    public function setPasswordRequestedAt(\DateTime $datetime=null) {
        $this->passwordRequestedAt = $datetime;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmationToken() {
        return $this->confirmationToken;
    }

    /**
     * @param $token
     * @return User
     */
    public function setConfirmationToken($token=null) {
        $this->confirmationToken = $token;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getConfirmationRequestedAt() {
        return $this->confirmationRequestedAt;
    }

    /**
     * @param \DateTime $requestDate
     * @return User
     */
    public function setConfirmationRequestedAt(\DateTime $requestDate=null) {
        $this->confirmationRequestedAt = $requestDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getConfirmationTokenValidTill() {
        return $this->confirmationTokenValidTill;
    }

    public function setConfirmationTokenValidTill(\DateTime $tokenValidTill=null) {
        $this->confirmationTokenValidTill = $tokenValidTill;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastlogin() {
        return $this->lastlogin;
    }

    /**
     * @param \DateTime $lastlogin_datetime
     * @return User
     */
    public function setLastlogin(\DateTime $lastlogin_datetime) {
        $this->lastlogin = $lastlogin_datetime;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return !empty($this->name) ? $this->name : $this->getUsername();
    }

    public function isTokenValid() {
        if (empty($this->confirmationToken)) {
            return false;
        }

        if (empty($this->confirmationTokenValidTill)) {
            return false;
        }

        $today = new \DateTime();
        if ($this->confirmationTokenValidTill <= $today) {
            return false;
        }

        return true;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar() {
        if (empty($this->avatar)) {
            return $this->getDefaultAvatar();
        }
        return $this->avatar;
    }

    public function getDefaultAvatar() {
        return 'bundles/homefinance/images/avatar/default.png';
    }

    public function hasAvatar() {
        if (empty($this->avatar)) {
            return false;
        }
        return true;
    }

    /**
     * @return Administration
     */
    public function getCurrentAdministration() {
        return $this->current_administration;
    }

    /**
     * @param Administration|null $administration
     * @return User
     */
    public function setCurrentAdministration(Administration $administration=null) {
        $this->current_administration = $administration;
        return $this;
    }

    /**
     * @param $avatar
     * @return User
     */
    public function setAvatar($avatar=null) {
        $this->avatar = $avatar;
        return $this;
    }

    public function __toString()
    {
        return (string) $this->getUsername();
    }
}
