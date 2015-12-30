<?php

namespace HomefinanceBundle\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('confirmationToken', 'text', array(
            'label' => 'confirmation.token.label',
            'attr' => array(
                'placeholder' => 'confirmation.token.placeholder',
                'help_text' => 'confirmation.token.help_text'
            ),
        ));
        $builder->add('save', 'submit', array(
            'label' => 'confirmation.confirm.btn-label',
            'attr' => array(
                'class' => 'btn btn-lg btn-primary',
            ),
        ));
    }

    public function getName()
    {
        return 'confirmation';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\User\Model\Confirmation',
            'translation_domain' => 'user',
        ));
    }
}