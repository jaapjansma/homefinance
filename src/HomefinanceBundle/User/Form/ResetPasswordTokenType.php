<?php

namespace HomefinanceBundle\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordTokenType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('confirmationToken', 'text', array(
            'label' => 'reset_password.token.label',
            'attr' => array(
                'placeholder' => 'reset_password.token.placeholder',
                'help_text' => 'reset_password.token.help_text'
            ),
        ));
        $builder->add('save', 'submit', array(
            'label' => 'reset_password.token.btn-label',
            'attr' => array(
                'class' => 'btn btn-lg btn-primary',
            ),
        ));
    }

    public function getName()
    {
        return 'reset_password_token';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\User\Model\ResetPassword',
            'translation_domain' => 'user',
            'validation_groups' => array('ResetPassword'),
        ));
    }
}