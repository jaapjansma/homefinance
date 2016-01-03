<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Administration\Filter;

use HomefinanceBundle\Administration\Manager\CategoryManager;
use HomefinanceBundle\Administration\Manager\TagManager;
use HomefinanceBundle\Administration\Manager\TransactionManager;
use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Util\DateUtil;
use Symfony\Component\Translation\TranslatorInterface;

class FilterFactory {

    /**
     * @var TransactionManager
     */
    protected $transactionManager;

    /**
     * @var CategoryManager
     */
    protected $categoryManager;

    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @var DateUtil
     */
    protected $dateUtil;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(TransactionManager $transactionManager, CategoryManager $categoryManager, TagManager $tagManager, DateUtil $dateUtil, TranslatorInterface $translator)
    {
        $this->transactionManager = $transactionManager;
        $this->categoryManager = $categoryManager;
        $this->tagManager = $tagManager;
        $this->dateUtil = $dateUtil;
        $this->translator = $translator;
    }

    public function getYears(Administration $administration) {
        return $this->transactionManager->getDistinctYears($administration);
    }

    public function getTags(Administration $administration) {
        return $this->tagManager->listAllTags($administration);
    }

    public function getTag(Administration $administration, $tagSlug) {
        $tags = $this->tagManager->listAllTags($administration);
        foreach($tags as $tag) {
            if ($tag->getSlug() == $tagSlug) {
                return $tag->getName();
            }
        }
        return null;
    }

    public function getCategories(Administration $administration) {
        return $this->categoryManager->allCategories($administration);
    }

    public function getCategory(Administration $administration, $categorySlug) {
        if ($categorySlug == 'empty') {
            return $this->translator->trans('transaction.filter.no-category', array(), 'administration');
        }
        $categories = $this->categoryManager->allCategories($administration);
        foreach ($categories as $cat) {
            if ($cat->getSlug() == $categorySlug) {
                return $cat->getIndentedTitle();
            }
        }
        return null;
    }

    public function getMonths() {
        return $this->dateUtil->getMonths();
    }

    public function getMonth(Administration $administration, $month) {
        $months = $this->dateUtil->getMonths();
        return $months[$month];
    }
}