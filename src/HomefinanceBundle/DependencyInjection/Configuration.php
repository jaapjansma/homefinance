<?php

namespace HomefinanceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /** @var string */
    const DEFAULT_TREEGRID_PATH = '%kernel.root_dir%/../vendor/maxazan/jquery-treegrid';

    const DEFAULT_TREEGRID_CSS = '/css/jquery.treegrid.css';

    const DEFAULT_TREEGRID_JS = '/js/jquery.treegrid.js';

    const DEFAULT_TREEGRID_BOOTSTRAP3_JS = '/js/jquery.treegrid.bootstrap3.js';

    const DEFAULT_DATE_PICKER_PATH = '%kernel.root_dir%/../vendor/eternicode/bootstrap-datepicker';

    const DEFAULT_DATE_PICKER_JS = '/dist/js/bootstrap-datepicker.js';

    const DEFAULT_DATE_PICKER_I18N_JS = '/dist/locales/';

    const DEFAULT_DATE_PICKER_CSS = '/dist/css/bootstrap-datepicker3.css';



    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('homefinance');

        $rootNode
            ->children()
            ->arrayNode('from_email')
                ->children()
                    ->scalarNode('address')->end()
                    ->scalarNode('sender_name')->end()
                ->end()
            ->end()
            ->scalarNode('jquery_treegrid_path')->defaultValue(self::DEFAULT_TREEGRID_PATH)->end()
            ->scalarNode('jquery_treegrid_css')->defaultValue(self::DEFAULT_TREEGRID_CSS)->end()
            ->scalarNode('jquery_treegrid_js')->defaultValue(self::DEFAULT_TREEGRID_JS)->end()
            ->scalarNode('jquery_treegrid_bootstrap3_js')->defaultValue(self::DEFAULT_TREEGRID_BOOTSTRAP3_JS)->end()
            ->scalarNode('bootstrap_datepicker_path')->defaultValue(self::DEFAULT_DATE_PICKER_PATH)->end()
            ->scalarNode('bootstrap_datepicker_js')->defaultValue(self::DEFAULT_DATE_PICKER_JS)->end()
            ->scalarNode('bootstrap_datepicker_i18n_js')->defaultValue(self::DEFAULT_DATE_PICKER_I18N_JS)->end()
            ->scalarNode('bootstrap_datepicker_css')->defaultValue(self::DEFAULT_DATE_PICKER_CSS)->end()
            ->arrayNode('auto_configure')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('assetic')->defaultValue(true)->end()
                ->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}