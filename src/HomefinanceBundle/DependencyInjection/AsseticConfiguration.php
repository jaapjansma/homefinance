<?php

namespace HomefinanceBundle\DependencyInjection;

class AsseticConfiguration {

    /**
     * Builds the assetic configuration.
     *
     * @param array $config
     *
     * @return array
     */
    public function build(array $config) {
        $output = array();

        $output['treegrid_css'] = $this->buildTreegridCss($config);
        $output['treegrid_js'] = $this->buildTreegridJs($config);

        $output['datepicker_css'] = $this->buildDatepickerCss($config);
        $output['datepicker_js'] = $this->buildDatepickerJs($config);

        return $output;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    protected function buildTreegridCss(array $config)
    {
        return array(
            'inputs'  => array(
                $config['jquery_treegrid_path'].$config['jquery_treegrid_css'],
                ),
            'filters' => array('cssrewrite'),
            'output'  => 'css/jquery.treegrid.css'
        );
    }

    protected function buildTreegridJs(array $config) {
        return array(
            'inputs'  => array(
                $config['jquery_treegrid_path'].$config['jquery_treegrid_js'],
                $config['jquery_treegrid_path'].$config['jquery_treegrid_bootstrap3_js'],
            ),
            'output'        => 'js/jquery.treegrid.js'
        );
    }

    protected function buildDatepickerCss(array $config)
    {
        return array(
            'inputs'  => array(
                $config['bootstrap_datepicker_path'].$config['bootstrap_datepicker_css'],
            ),
            'filters' => array('cssrewrite'),
            'output'  => 'css/bootstrap-datepicker.css'
        );
    }

    protected function buildDatepickerJs(array $config) {
        return array(
            'inputs'  => array(
                $config['bootstrap_datepicker_path'].$config['bootstrap_datepicker_js'],
                $config['bootstrap_datepicker_path'].$config['bootstrap_datepicker_i18n_js'].'bootstrap-datepicker.nl.min.js',

            ),
            'output'        => 'js/bootstrap-datepicker.js'
        );
    }

}