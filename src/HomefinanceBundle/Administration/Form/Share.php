<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Administration\Form;

use HomefinanceBundle\Share\Permission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Share extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'label' => 'share.email.label',
                'required' => true,
                'mapped' => false,
            ))
            ->add('permission', 'choice', array(
                'choices' => $this->getShareChoices(),
                'label' => 'share.access.label',
                'mapped' => false,
            ))
            ->add('save', 'submit', array(
                'label' => 'share.update.btn-label',
                'attr' => array(
                    'class' => 'btn btn-lg btn-primary',
                ),
            ))
        ;
    }

    protected function getShareChoices() {
        $choices[Permission::VIEW] = Permission::VIEW;
        $choices[Permission::EDIT] = Permission::EDIT;
        $choices[Permission::FULL_ACCESS] = Permission::FULL_ACCESS;
        return $choices;
    }

    public function getName()
    {
        return 'share_edit';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomefinanceBundle\Entity\Share',
            'translation_domain' => 'administration',
        ));
    }

}