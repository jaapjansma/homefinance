<?php

namespace HomefinanceBundle\Entity;

use HomefinanceBundle\Share\Permission;
use HomefinanceBundle\Share\Exception\InvalidSharePermissionException;
use Doctrine\ORM\Mapping as ORM;

/**
 * Share
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Share
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Administration
     *
     * @ORM\ManyToOne(targetEntity="Administration", inversedBy="shares")
     * @ORM\JoinColumn(name="administration_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $administration;

    /**
     * @var string
     *
     * @ORM\Column(name="permission", type="string", length=255)
     */
    private $permission;


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
     * Set user
     *
     * @param User $user
     * @return Share
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set administration
     *
     * @param Administration $administration
     * @return Share
     */
    public function setAdministration(Administration $administration)
    {
        $this->administration = $administration;

        return $this;
    }

    /**
     * Get administration
     *
     * @return Administration
     */
    public function getAdministration()
    {
        return $this->administration;
    }

    /**
     * Set permission
     *
     * @param string $permission
     * @throws InvalidSharePermissionException
     * @return Share
     */
    public function setPermission($permission)
    {
        if (!Permission::isPermissionValid($permission)) {
            throw new InvalidSharePermissionException();
        }
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission
     *
     * @return string 
     */
    public function getPermission()
    {
        return $this->permission;
    }
}
