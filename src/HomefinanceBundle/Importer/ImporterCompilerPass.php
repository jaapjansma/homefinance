<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Importer;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ImporterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has('homefinance.importer.factory')) {
            $factory = $container->findDefinition('homefinance.importer.factory');

            $importers = $container->findTaggedServiceIds('homefinance.importer');
            foreach ($importers as $id => $importer) {
                foreach ($importer as $attributes) {
                    $factory->addMethodCall('addImporter', array(new Reference($id), $attributes["alias"]));
                }
            }
        }
    }
}