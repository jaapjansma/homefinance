<?php

namespace HomefinanceBundle\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\AccessMapInterface;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface {

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $securityChecker;

    /**
     * @var AccessMapInterface
     */
    protected $accessMap;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(AuthorizationCheckerInterface $securityChecker, AccessMapInterface $accessMap, RouterInterface $router) {
        $this->securityChecker = $securityChecker;
        $this->accessMap = $accessMap;
        $this->router = $router;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException){
        list($roles,$channel) = $this->accessMap->getPatterns($request);
        if ($this->securityChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            //non anonymous user
            //check if the resource request has IS_ANONYMOUS
            //if so redirect to dashboard
            foreach($roles as $role) {
                if ($role == 'IS_ANONYMOUS') {
                    return new RedirectResponse($this->router->generate('dashboard'));
                }
            }
        }
        return null;
    }

}