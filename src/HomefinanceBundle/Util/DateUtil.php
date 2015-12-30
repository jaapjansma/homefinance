<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Util;

use Symfony\Component\Translation\TranslatorInterface;

class DateUtil {

    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getMonths() {
        $months = array(
            1 => 'months.january',
            2 => 'months.february',
            3 => 'months.march',
            4 => 'months.april',
            5 => 'months.may',
            6 => 'months.june',
            7 => 'months.july',
            8 => 'months.august',
            9 => 'months.september',
            10 => 'months.october',
            11 => 'months.november',
            12 => 'months.december',
        );

        foreach($months as $month => $month_name) {
            $months[$month] = $this->translator->trans($month_name, array(), 'administration');
        }

        return $months;
    }
}