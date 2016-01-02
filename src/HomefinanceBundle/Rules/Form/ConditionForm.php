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

class ConditionForm extends AbstractType
{

    /**
     * @var Factory
     */
    protected $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    protected function getConditions() {
        $return = array();
        foreach($this->factory->getAllConditions() as $alias => $condition) {
            $return[$alias] = $condition->getName();
        }
        return $return;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('condition', 'choice', array(
                'label' => 'rules.condition.condition.label',
                'required' => true,
                'choices' => $this->getConditions(),
                'empty_data'  => null,
                'empty_value' => "rules.condition.condition.empty",
            ))
            ->add('actions', 'form_actions');

        $builder->get('actions')
            ->add('save', 'submit', array(
                'label' => 'rules.condition.update.btn-label',
                'attr' => array(
                    'class' => 'btn btn-lg btn-success',
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'rules_condition';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\Entity\RuleCondition',
            'translation_domain' => 'rules',
        ));
    }

}