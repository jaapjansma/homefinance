<?php

namespace HomefinanceBundle\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
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
            ->add('save', 'submit', array(
                'label' => 'profile.update.btn-label',
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary',
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'profile';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\User\Model\Profile',
            'translation_domain' => 'user',
        ));
    }

}