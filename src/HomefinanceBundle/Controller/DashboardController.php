<?php

namespace HomefinanceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomefinanceBundle\DefaultController\DefaultController;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends DefaultController {

    /**
     * @Route("/", name="dashboard")
     */
    public function dashboardAction(Request $request) {
        //return $this->render('HomefinanceBundle:Dashboard:dashboard.html.twig');
        return $this->redirect($this->generateUrl('transactions_by_category'));
    }

}