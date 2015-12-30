<?php

namespace HomefinanceBundle\Administration\Form;

use HomefinanceBundle\Administration\Manager\CategoryManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Category extends AbstractType
{

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
            ->add('parent', 'entity', array(
                'label' => 'category.parent.label',
                'class' => 'HomefinanceBundle:Category',
                'choice_label' => 'title',
                'choices' => $this->getCategoryParents($builder->getData()),
            ))
            ->add('title', 'text', array(
                'label' => 'category.title.label',
                'required' => true,
            ))
            ->add('type', 'choice', array(
                'choices' => $this->getCategoryTypes(),
                'required' => true,
                'expanded' => true,
                'label' => 'category.type.label',
            ))
            ->add('save', 'submit', array(
                'label' => 'category.update.btn-label',
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary',
                ),
            ))
        ;
    }

    protected function getCategoryParents(\HomefinanceBundle\Entity\Category $category) {
        return $this->categoryManager->allToplevelOrLevelOne();
    }

    protected function getCategoryTypes() {
        $types = \HomefinanceBundle\Category\Type::getAllTypes();
        foreach($types as $type) {
            $return[$type] = $type;
        }
        return $return;
    }

    public function getName()
    {
        return 'category';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\Entity\Category',
            'translation_domain' => 'administration',
        ));
    }

}