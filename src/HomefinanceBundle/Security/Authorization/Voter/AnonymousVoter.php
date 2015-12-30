<?php

namespace HomefinanceBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 * @see http://henrik.bjrnskov.dk/symfony2-anonymous-users-access/
 */
class AnonymousVoter implements VoterInterface
{
    /**
     * The role check agains
     */
    const IS_ANONYMOUS = 'IS_ANONYMOUS';

    /**
     * @var AuthenticationTrustResolverInterface $authenticationTrustResolver
     */
    protected $authenticationTrustResolver;

    /**
     * @param AuthenticationTrustResolverInterface $authenticationTrustResolver
     */
    public function __construct(AuthenticationTrustResolverInterface $authenticationTrustResolver)
    {
        $this->authenticationTrustResolver = $authenticationTrustResolver;
    }

    /**
     * @param string $attribute
     * @return Boolean
     */
    public function supportsAttribute($attribute)
    {
        return static::IS_ANONYMOUS === $attribute;
    }

    /**
     * @param string $class
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return true;
    }

    /**
     * Only allow access if the TokenInterface isAnonymous. But abstain from voting
     * if the attribute IS_ANONYMOUS isnt supported.
     *
     * @param TokenInterface $token
     * @param object $object
     * @param array $attributes
     * @return integer
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            // If the user is anonymous then grant access otherwise deny!
            if ($this->authenticationTrustResolver->isAnonymous($token)) {
                return VoterInterface::ACCESS_GRANTED;
            }

            return VoterInterface::ACCESS_DENIED;
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}