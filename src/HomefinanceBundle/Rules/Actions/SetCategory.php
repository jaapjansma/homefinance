<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Rules\Actions;

use Doctrine\ORM\EntityManagerInterface;
use HomefinanceBundle\Administration\Manager\CategoryManager;
use HomefinanceBundle\Rules\ActionInterface;
use HomefinanceBundle\Entity\RuleAction;
use HomefinanceBundle\Entity\Transaction;
use HomefinanceBundle\Rules\Actions\Form\SetCategory as SetCategoryForm;
use Symfony\Component\Form\Form;
use Symfony\Component\Translation\TranslatorInterface;

class SetCategory implements ActionInterface {

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var CategoryManager
     */
    protected $categoryManager;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, CategoryManager $categoryManager)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->categoryManager = $categoryManager;
    }

    /**
     * Executes the action
     *
     * @param Transaction $transaction
     * @param RuleAction $action
     * @return bool
     */
    public function executeAction(Transaction $transaction, RuleAction $action) {
        $params = $this->getParams($action);
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Category');
        $category = $repo->findOneBy(array(
            'id' => $params['category_id']
        ));

        $transaction->setCategory($category);
    }

    /**
     * @param RuleAction $action
     * @return Form
     */
    public function getForm(RuleAction $action) {
        return new SetCategoryForm($this->categoryManager);
    }

    public function setFormData(Form $form, RuleAction $action) {
        $params = $this->getParams($action);
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Category');
        $category = $repo->findOneBy(array('id' => $params['category_id']));
        $form->get('category')->setData($category);
    }


    /**
     * @param Form $form
     * @param RuleAction $action
     * @return void
     */
    public function formIsSubmitted(Form $form, RuleAction $action) {
        $params = $this->getParams($action);
        $category = $form->get('category')->getData();
        $params['category_id'] = $category->getId();

        $action->setRawParams($params);
    }

    /**
     * @param RuleAction $action
     * @return string
     */
    public function getLabel(RuleAction $action) {
        $params = $this->getParams($action);
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Category');
        $category = $repo->findOneBy(array('id' => $params['category_id']));


        return $this->translator->trans('rules.actions.set_category.label', array('%category%' => $category->getTitle()), 'rules');
    }

    public function hasForm(RuleAction $action) {
        return true;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->translator->trans('rules.actions.set_category.name', array(), 'rules');
    }

    protected function getParams(RuleAction $action) {
        $params = $action->getRawParams();
        if (empty($params) || !is_array($params)) {
            $params = array();
        }
        if (!isset($params['category_id'])) {
            $params['category_id'] = '';
        }
        return $params;
    }

}