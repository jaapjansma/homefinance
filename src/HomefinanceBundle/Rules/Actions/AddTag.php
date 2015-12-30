<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Rules\Actions;

use Doctrine\ORM\EntityManagerInterface;
use HomefinanceBundle\Administration\Manager\TagManager;
use HomefinanceBundle\Rules\ActionInterface;
use HomefinanceBundle\Entity\RuleAction;
use HomefinanceBundle\Entity\Transaction;
use HomefinanceBundle\Rules\Actions\Form\AddTagForm;
use Symfony\Component\Form\Form;
use Symfony\Component\Translation\TranslatorInterface;

class AddTag implements ActionInterface {

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var TagManager
     */
    protected $tagManager;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, TagManager $tagManager)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->tagManager = $tagManager;
    }

    /**
     * Executes the action
     *
     * @param Transaction $transaction
     * @param RuleAction $action
     * @return bool
     */
    public function executeAction(Transaction $transaction, RuleAction $action) {
        $params = $this->getParams($action);
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Tag');

        foreach($params['tag_ids'] as $tag_id) {
            $tag = $repo->findOneBy(array(
                'id' => $tag_id
            ));
            if ($tag && !$transaction->getTags()->contains($tag)) {
                $transaction->getTags()->add($tag);
            }
        }
    }

    /**
     * @param RuleAction $action
     * @return Form
     */
    public function getForm(RuleAction $action) {
        return new AddTagForm($this->tagManager);
    }

    public function setFormData(Form $form, RuleAction $action) {
        $params = $this->getParams($action);
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Tag');
        $tags = array();
        foreach($params['tag_ids'] as $tag_id) {
            $tag = $repo->findOneBy(array(
                'id' => $tag_id
            ));
            if ($tag) {
                $tags[] = $tag;
            }
        }
        $form->get('tags')->setData($tags);
    }


    /**
     * @param Form $form
     * @param RuleAction $action
     * @return void
     */
    public function formIsSubmitted(Form $form, RuleAction $action) {
        $params = $this->getParams($action);
        $tags = $form->get('tags')->getData();
        $params['tag_ids'] = array();
        foreach($tags as $tag) {
            $params['tag_ids'][] = $tag->getId();
        }
        $action->setRawParams($params);
    }

    /**
     * @param RuleAction $action
     * @return string
     */
    public function getLabel(RuleAction $action) {
        $params = $this->getParams($action);
        $repo = $this->entityManager->getRepository('HomefinanceBundle:Tag');
        $tags = array();
        foreach($params['tag_ids'] as $tag_id) {
            $tag = $repo->findOneBy(array(
                'id' => $tag_id
            ));
            if ($tag) {
                $tags[] = $tag->getName();
            }
        }


        return $this->translator->trans('rules.actions.add_tag.label', array('%tags%' => implode(",", $tags)), 'rules');
    }

    public function hasForm(RuleAction $action) {
        return true;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->translator->trans('rules.actions.add_tag.name', array(), 'rules');
    }

    protected function getParams(RuleAction $action) {
        $params = $action->getRawParams();
        if (empty($params) || !is_array($params)) {
            $params = array();
        }
        if (!isset($params['tag_ids']) || !is_array($params['tag_ids'])) {
            $params['tag_ids'] = array();
        }
        return $params;
    }

}