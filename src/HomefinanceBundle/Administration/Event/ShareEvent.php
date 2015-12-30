<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Administration\Event;

use HomefinanceBundle\Entity\Share;
use Symfony\Component\EventDispatcher\Event;
use HomefinanceBundle\User\Entity\User;

class ShareEvent extends Event {

    /**
     * @var User
     */
    protected $by;

    /**
     * @var Share
     */
    protected $share;

    public function __construct(Share $share, User $user)
    {
        $this->by = $user;
        $this->share = $share;
    }

    public function getShare() {
        return $this->share;
    }

    public function getBy() {
        return $this->by;
    }

}