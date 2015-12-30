<?php

namespace HomefinanceBundle\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', 'text', array(
            'label' => 'user.name.label',
        ))
        ->add('email', 'text', array(
            'label' => 'user.email.label'
        ))
        ->add('password', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'user.password.invalid-message',
            'options' => array('attr' => array('class' => 'password-field')),
            'first_options'  => array('label' => 'user.password.label1'),
            'second_options' => array('label' => 'user.password.label2'),
        ))
        ->add('administration_name', 'text', array(
            'label' => 'user.administration_name.label',
            'attr' => array(
                'help_text' => 'user.administration_name.help_text'
            )
        ))
        ->add('agree', 'checkbox', array(
            'label' => 'user.agree',
            'required' => false,
        ))
        ->add('save', 'submit', array(
            'label' => 'user.register.btn-label',
            'attr' => array(
                'class' => 'btn btn-lg btn-primary',
            ),
        ))
        ;
    }

    public function getName()
    {
        return 'registration';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\User\Model\Registration',
            'translation_domain' => 'user',
        ));
    }

}