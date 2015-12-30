<?php

namespace HomefinanceBundle\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('confirmationToken', 'hidden');
        $builder->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'user.password.invalid-message',
            'options' => array('attr' => array('class' => 'password-field')),
            'first_options'  => array('label' => 'user.password.label1'),
            'second_options' => array('label' => 'user.password.label2'),
        ));
        $builder->add('save', 'submit', array(
            'label' => 'reset_password.btn-label',
            'attr' => array(
                'class' => 'btn btn-lg btn-primary',
            ),
        ));
    }

    public function getName()
    {
        return 'reset_password';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\User\Model\ResetPassword',
            'translation_domain' => 'user',
            'validation_groups' => array('password'),
        ));
    }
}