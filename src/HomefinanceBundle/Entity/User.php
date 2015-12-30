<?php

namespace HomefinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HomefinanceBundle\User\Entity\User as BaseUser;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User extends BaseUser
{

}
