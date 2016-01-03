<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Rules\Conditions\Form;

use HomefinanceBundle\Rules\Conditions\Form;
use Symfony\Component\Form\FormBuilderInterface;

class DescriptionContainsForm extends Form
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', 'text', array(
                'label' => 'rules.condition.description_contains.description.label',
                'required' => true,
                'mapped' => false,
            ))
        ;

        parent::buildForm($builder, $options);
    }

    public function getName()
    {
        return 'rules_condition_description_contains_with';
    }

}