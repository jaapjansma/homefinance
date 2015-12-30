<?php

namespace HomefinanceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;


class HomefinanceExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('homefinance.from_email.address', $config['from_email']['address']);
        $container->setParameter('homefinance.from_email.sender_name', $config['from_email']['sender_name']);

        $container->setParameter('homefinance.jquery_treegrid_path', $config['jquery_treegrid_path']);
        $container->setParameter('homefinance.jquery_treegrid_js', $config['jquery_treegrid_js']);
        $container->setParameter('homefinance.jquery_treegrid_bootstrap3_js', $config['jquery_treegrid_bootstrap3_js']);
        $container->setParameter('homefinance.jquery_treegrid_css', $config['jquery_treegrid_css']);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container) {
        $bundles = $container->getParameter('kernel.bundles');
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);
        // Configure Assetic if AsseticBundle is activated and the option
        // "braincrafted_bootstrap.auto_configure.assetic" is set to TRUE (default value).
        if (true === isset($bundles['AsseticBundle']) && true === $config['auto_configure']['assetic']) {
            $this->configureAsseticBundle($container, $config);
        }
    }

    /**
     * @param ContainerBuilder $container The service container
     * @param array            $config    The bundle configuration
     *
     * @return void
     */
    protected function configureAsseticBundle(ContainerBuilder $container, array $config)
    {
        foreach (array_keys($container->getExtensions()) as $name) {
            switch ($name) {
                case 'assetic':
                    $asseticConfig = new AsseticConfiguration;
                    $container->prependExtensionConfig(
                        $name,
                        array('assets' => $asseticConfig->build($config))
                    );
                    break;
            }
        }
    }
}