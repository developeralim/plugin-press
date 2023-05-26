<?php
    /**
     * Plugin press assets class
     * 
     * @package ASSETS
     * 
     * @since 1.0.0
     * 
     * @author Md Alim <developeralimcu@gmail.com>
     */
    namespace PluginPress;

    class Assets {

        /**
         * Construct Assets class and register asset action
         * @return void
         * @since 1.0.0
         *
         */

        public function __construct()
        {
            add_action( 'admin_init',[$this,'register_all_scripts'] );

            if( ! is_admin(  ) ) {
                add_action( 'wp_enqueue_scripts',[$this,'register_all_scripts'],5 );
            }
        }

        /**
         * Register all styles and scripts
         * @since 1.0.0
         * 
         * @return void
         */
        public function register_all_scripts()
        {
            $this->register_styles($this->get_styles());
            $this->register_scripts($this->get_scripts());
        }
        /**
         * Enlist all style sheets
         * @return array
         * 
         * @since 1.0.0
         * 
         */
        private function get_styles()
        {
            return [
                'plugin-press-admin-css' => [
                    'src'       => PLUGIN_URL . '/assets/css/style.css',
                    'version'   => PLUGIN_VERSION,
                    'deps'      => ['wp-components'],
                ]
            ];
        }

        /**
         * Enlist all scripts
         * 
         * @return  array
         * 
         * @since 1.0.0
         */

        private function get_scripts()
        {
            return [
                'plugin-press-admin-script' => [
                    'src'       => PLUGIN_URL . '/assets/js/script.js',
                    'version'   => PLUGIN_VERSION,
                    'deps'      => [ 'jquery' ],
                    'in_footer' => true,
                ]
            ];
        }
        /**
         * Register all styles
         * 
         * @return  void
         * @param array $styles refers to all styles contained array
         * @since 1.0.0
         */
        private function register_styles( $styles )
        {
            foreach($styles as $handle => $style){

                $deps = $style['deps'] ? $style['deps'] : false;
         
                wp_register_style( $handle, $style['src'], $deps, $style['version'] );
            }
        }

        /**
         * Register all scripts
         * 
         * @return  void
         * @param array $scripts refers to all scripts contained array
         * @since 1.0.0
         */
        private function register_scripts( $scripts )
        {
            foreach ($scripts as $handle => $script) {

                $deps = $script['deps'] ? $script['deps'] : false;

                wp_register_script( $handle, $script['src'], $deps,$script['version'],$script['in_footer'] ?? false );

            }
        }
    }