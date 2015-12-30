<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Rules\Form;

use HomefinanceBundle\Rules\Factory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionForm extends AbstractType
{

    /**
     * @var Factory
     */
    protected $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    protected function getActions() {
        $return = array();
        foreach($this->factory->getAllActions() as $alias => $action) {
            $return[$alias] = $action->getName();
        }
        return $return;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('action', 'choice', array(
                'label' => 'rules.action.action.label',
                'required' => true,
                'choices' => $this->getActions(),
                'empty_data'  => null,
                'empty_value' => "rules.action.action.empty",
            ))
            ->add('save', 'submit', array(
                'label' => 'rules.action.update.btn-label',
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary',
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'rules_action';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\Entity\RuleAction',
            'translation_domain' => 'rules',
        ));
    }

}