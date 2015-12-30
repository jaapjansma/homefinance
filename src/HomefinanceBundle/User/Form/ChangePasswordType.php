<?php

namespace HomefinanceBundle\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('currentPassword', 'password', array(
            'label' => 'user.your_current_password'
        ));
        $builder->add('newPassword', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'user.password.invalid-message',
            'options' => array('attr' => array('class' => 'password-field')),
            'first_options'  => array('label' => 'user.password.label1'),
            'second_options' => array('label' => 'user.password.label2'),
        ));
        $builder->add('save', 'submit', array(
            'label' => 'change_password.update.btn-label',
            'attr' => array(
                'class' => 'btn btn-lg btn-primary',
            ),
        ));
    }

    public function getName()
    {
        return 'change_password';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\User\Model\ChangePassword',
            'translation_domain' => 'user',
        ));
    }
}