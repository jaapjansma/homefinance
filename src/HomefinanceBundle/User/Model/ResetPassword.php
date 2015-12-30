<?php

namespace HomefinanceBundle\User\Model;

use HomefinanceBundle\User\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use HomefinanceBundle\User\Validator\Constraints\ValidConfirmationToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Assert\GroupSequence({"ResetPassword", "token", "password"})
 */
class ResetPassword {

    /**
     * @var string
     * @Assert\NotBlank(groups={"password"})
     * @Assert\Length(min=8,max = 4096,groups={"password"})
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ValidConfirmationToken()
     */
    private $confirmationToken;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getConfirmationToken() {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($token) {
        $this->confirmationToken = $token;
    }

    public function updateUser(User $user) {
        $user->setPassword($this->encoder->encodePassword($user, $this->password));
        return $user;
    }

}