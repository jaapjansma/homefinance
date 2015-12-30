<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
namespace HomefinanceBundle\Rules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class RuleCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has('homefinance.rules.factory')) {
            $factory = $container->findDefinition('homefinance.rules.factory');

            $conditions = $container->findTaggedServiceIds('rules.conditions');
            foreach ($conditions as $id => $condition) {
                foreach ($condition as $attributes) {
                    $factory->addMethodCall('addCondition', array(new Reference($id), $attributes["alias"]));
                }
            }

            $actions = $container->findTaggedServiceIds('rules.actions');
            foreach ($actions as $id => $action) {
                foreach ($action as $attributes) {
                    $factory->addMethodCall('addAction', array(new Reference($id), $attributes["alias"]));
                }
            }
        }
    }
}