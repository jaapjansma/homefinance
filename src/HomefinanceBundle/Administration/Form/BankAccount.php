<?php

namespace HomefinanceBundle\Administration\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankAccount extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'bank_account.name.label',
                'required' => true,
            ))
            ->add('iban', 'text', array(
                'label' => 'bank_account.iban.label',
                'required' => true,
            ))
            ->add('starting_balance', 'money', array(
                'label' => 'bank_account.starting_balance.label',
                'required' => true,
                'currency' => 'EUR',
            ))
            ->add('save', 'submit', array(
                'label' => 'bank_account.update.btn-label',
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary',
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'bank_account';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\Entity\BankAccount',
            'translation_domain' => 'administration',
        ));
    }

}