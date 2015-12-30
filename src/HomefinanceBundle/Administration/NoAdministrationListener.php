<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Administration;

use HomefinanceBundle\Administration\Exception\NoAdministrationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

class NoAdministrationListener {

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(Router $router, Session $session, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->session = $session;
        $this->translator = $translator;
    }

    public function onKernelException(GetResponseForExceptionEvent $event) {
        $exception = $event->getException();
        if ($exception instanceof NoAdministrationException) {
            $this->session->getFlashBag()->add('danger', $this->translator->trans('administration.none_found', array()));
            $response = new RedirectResponse($this->router->generate('manage_administrations'));
            $event->setResponse($response, 307);
        }
    }

}