<?php

namespace HomefinanceBundle\User\Model;

use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\User\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use HomefinanceBundle\User\Validator\Constraints\UniqueEmailAddress;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Registration {

    /**
     * @var string
     */
    public $administration_name;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @UniqueEmailAddress()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=8,max = 4096)
     */
    public $password;

    /**
     * @var bool
     * @Assert\True(message="user.agree.required")
     */
    public $agree;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->passwordEncoder = $encoder;
    }

    public function updateAdministration(Administration $administration) {
        $name = empty($this->administration_name) ? $this->name : $this->administration_name;
        $administration->setName($name);
    }

    public function updateUser(User $user) {
        $user->setEmail($this->email);
        $user->setName($this->name);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $this->password));
    }

}