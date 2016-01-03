<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Administration\Filter;

use Symfony\Component\HttpFoundation\Session\Session;

class FilterBag {

    /**
     * @var Session
     */
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param $name
     * @return Filter
     */
    public function get($name, $reset=false) {
        if (!$this->session->has('filter_'.$name) || $reset) {
            $this->session->set('filter_'.$name, new Filter());
        }
        return $this->session->get('filter_'.$name);
    }

    /**
     * @param $name
     * @param Filter $filter
     * @return Filter
     */
    public function set($name, Filter $filter) {
        $this->session->set('filter_'.$name, $filter);
        return $filter;
    }

}