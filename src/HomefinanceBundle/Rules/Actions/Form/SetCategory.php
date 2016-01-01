<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Rules\Actions\Form;

use HomefinanceBundle\Administration\Manager\CategoryManager;
use HomefinanceBundle\Rules\Actions\Form;
use Symfony\Component\Form\FormBuilderInterface;

class SetCategory extends Form {

    /**
     * @var CategoryManager
     */
    protected $categoryManager;

    public function __construct(CategoryManager $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', 'entity', array(
                'class' => 'HomefinanceBundle:Category',
                'choices' => $this->categoryManager->allLeafCategories(),
                'choice_label' => 'indentedTitle',
                'label' => 'rules.action.set_category.category.label',
                'empty_data'  => null,
                'empty_value' => "rules.action.set_category.category.empty",
                'mapped' => false,
            ))
        ;

        parent::buildForm($builder, $options);
    }

    public function getName()
    {
        return 'rules_action_set_category';
    }

}