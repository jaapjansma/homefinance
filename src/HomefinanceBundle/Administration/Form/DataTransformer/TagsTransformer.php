<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Administration\Form\DataTransformer;

use HomefinanceBundle\Administration\Manager\TagManager;
use Symfony\Component\Form\DataTransformerInterface;

class TagsTransformer implements DataTransformerInterface {

    /**
     * @var TagManager
     */
    protected $tagManager;

    public function __construct(TagManager $tagManager) {
        $this->tagManager = $tagManager;
    }

    public function transform($tags) {
        $values = array();
        foreach($tags as $tag) {
            $values[] = $tag->getName();
        }
        return implode(",", $values);
    }

    public function reverseTransform($tags)
    {
        if(is_null($tags) || !$tags) {
            return;
        }

        $values = explode(",", $tags);
        $return = $this->tagManager->loadOrCreateTags($values);
        return $return;
    }

}
