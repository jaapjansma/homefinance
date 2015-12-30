<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\DefaultController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class DefaultController extends Controller {

    /**
     * @param $permission
     * @return Administration
     * @throw AccessDeniedHttpException
     */
    protected function checkCurrentAdministration($permission) {
        $user = $this->getUser();
        $manager = $this->get('homefinance.administration.manager');
        $accessManager = $this->get('homefinance.administration.access_manager');
        $administration = $manager->getCurrentAdministration($user);
        if ($accessManager->hasAccess($user, $administration, $permission)) {
            return $administration;
        }
        throw new AccessDeniedHttpException();
    }

}