<?php

namespace HomefinanceBundle\Administration\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use HomefinanceBundle\Administration\Form\Type\TagType;

class Transaction extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bank_account', 'entity', array(
                'class' => 'HomefinanceBundle:BankAccount',
                'choices' => $options['bank_accounts'],
                'choice_label' => 'label',
                'label' => 'transaction.bank_account.label',
                'required' => true,
            ))
            ->add('category', 'entity', array(
                'class' => 'HomefinanceBundle:Category',
                'choices' => $options['categories'],
                'choice_label' => 'indentedTitle',
                'label' => 'transaction.category.label',
                'empty_data'  => null,
                'empty_value' => "transaction.category.empty",
                'required' => false,
            ))
            ->add('date', 'date', array(
                'label' => 'transaction.date.label',
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'class' => 'form-control input-inline datepicker',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy',
                ],
                'required' => true,
            ))
            ->add('amount', 'money', array(
                'label' => 'transaction.amount.label',
                'required' => true,
                'currency' => 'EUR',
                'required' => true,
            ))
            ->add('name', 'text', array(
                'label' => 'transaction.name.label',
                'required' => true,
                'attr' => array(
                    'help_text' => 'transaction.name.help_text',
                ),
                'required' => false,
            ))
            ->add('iban', 'text', array(
                'label' => 'transaction.iban.label',
                'required' => false,
                'attr' => array(
                    'help_text' => 'transaction.iban.help_text',
                ),
                'required' => false,
            ))
            ->add('description', 'textarea', array(
                'label' => 'transaction.description.label',
                'required' => false,
            ))
            ->add(
                'tags',
                'tags',
                array(
                    'attr' => array(
                        'data-role' =>"tagsinput",
                    ),
                    'required' => false,
                )
            )
            ->add('save', 'submit', array(
                'label' => 'transaction.update.btn-label',
                'attr' => array(
                    'class' => 'btn btn-lg btn-default',
                ),
            ))
        ;
    }

    public function getName()
    {
        return 'transaction';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\Entity\Transaction',
            'translation_domain' => 'administration',
        ));

        $resolver->setDefined(array(
            'bank_accounts',
            'categories'
        ));
    }

}