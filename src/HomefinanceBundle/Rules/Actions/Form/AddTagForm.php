<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Rules\Actions\Form;

use HomefinanceBundle\Administration\Manager\TagManager;
use HomefinanceBundle\Rules\Actions\Form;
use Symfony\Component\Form\FormBuilderInterface;

class AddTagForm extends Form {

    /**
     * @var TagManager
     */
    protected $tagManager;

    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags', 'entity', array(
                'class' => 'HomefinanceBundle:Tag',
                'choices' => $this->tagManager->listAllTags(),
                'choice_label' => 'name',
                'label' => 'rules.action.add_tag.tags.label',
                'expanded' => true,
                'multiple' => true,
                'mapped' => false,
            ))
        ;

        parent::buildForm($builder, $options);
    }

    public function getName()
    {
        return 'rules_action_add_tag';
    }

}