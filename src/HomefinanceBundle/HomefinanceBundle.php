<?php

namespace HomefinanceBundle;

use HomefinanceBundle\Importer\ImporterCompilerPass;
use HomefinanceBundle\Rules\RuleCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HomefinanceBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RuleCompilerPass());
        $container->addCompilerPass(new ImporterCompilerPass());
    }
}
