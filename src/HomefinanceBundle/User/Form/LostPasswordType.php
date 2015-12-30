<?php

namespace HomefinanceBundle\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LostPasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('email', 'email', array(
            'label' => 'lost_password.email.label',
        ));
        $builder->add('save', 'submit', array(
            'label' => 'lost_password.confirm.btn-label',
            'attr' => array(
                'class' => 'btn btn-lg btn-primary',
            ),
        ));
    }

    public function getName()
    {
        return 'lost_password';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\User\Model\LostPassword',
            'translation_domain' => 'user',
        ));
    }
}