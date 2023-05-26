<?php
    /**
     * Plugin press admin class
     * 
     * @package Admin
     * 
     * @since 1.0.0
     * 
     * @author Md Alim <developeralimcu@gmail.com>
     */
    namespace PluginPress;

    class Admin {
        /**
         * Construct Admin class and register admin action
         * @return void
         * @since 1.0.0
         *
         */

        public function __construct()
        {
            $this->init_classes();
        }

        /**
         * Initialize all classes to load
         * @return void
         * @since 1.0.0
        */

        public function init_classes()
        {
            new \PluginPress\admin\Menu();
        }
    }