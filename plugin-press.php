<?php 
 /**
 * Plugin Name: Plugin Press
 * Plugin URI: https://wordpress.org/plugins/plugin-press/
 * Description: A test purpose created plugin
 * Version: 1.0.0
 * Author: Md Alim Khan
 * Author URI: https://partnerhives.com
 * License: GPLv2 or later
 * License URI: https://liscence.com/
 * Text Domain: plugin-press
 * Domain Path: /languages
 *
 * @package Plugin-press
 */

/**
 * Copyright (c) 2021 Kapil Paul (email: developeralimcu@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 */

//don't call the file directly

if ( ! defined('ABSPATH') ) {
    die('404 page not found');
}

require_once __DIR__.'/vendor/autoload.php';

/**
 * Plugin press class
 * 
 * @author Md Alim <developeralimcu>
 * 
 * @since 1.0.0
 */

final class PluginPress {

    /**
     * Plugin Version
     * 
     * @var string
     * 
     */
    
    const VERSION = '1.0.0';

    /**
     * Container to contain all class instances
     * 
     * @var array
     */
    private $container = [];

    /**
     * Construct the class
     * 
     * Setup all actions hooks of our plugin
     * 
     * @return void
     */
    private function __construct()
    {
        $this->define_constant();
        register_activation_hook( __FILE__, [$this,'activate'] );
        register_activation_hook( __FILE__, [$this,'deactivate'] );

        // plugin initialize hook
        add_action( 'plugins_loaded',[ $this, 'init_plugin' ]  );
    }

    /**
     * Call this function when plugin activates
     * 
     * @since 1.0.0
     * 
     * @return  void
     */
    public function activate()
    {

    }

    /**
     * Call this function when plugin deactivates
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function deactivate()
    {

    }

    /**
     * Fires when all plugin loaded
     * 
     * @since 1.0.0
     * 
     * @return  void
     */

    public function init_plugin()
    {
        $this->init_hooks();
    }

    /**
     * Includes all hooks of initialization plugin
     * 
     * @return void
     * 
     * @since 1.0.0
     *  
     */
    public function init_hooks()
    {
        add_action( 'init', [$this,'init_classes']);
        add_action( 'init', [$this,'localization_setup']);
    }

    /**
     * All classes which should be loaded after initialize admin
     * 
     * @since 1.0.0
     * 
     * @return void
     * 
     */
    public function init_classes()
    {
        if ( $this->is_request('admin') ) {
            $this->container['ajax'] = new \PluginPress\Admin();
        }

        if ( $this->is_request( 'ajax' ) ) {
            $this->container['ajax'] = new \PluginPress\Ajax();
        }

        if ( $this->is_request( 'frontend' ) ) {
            $this->container['frontend'] = new \PluginPress\Frontend();
        }

        //load other classes to container
        $this->container['assets']   = new \PluginPress\Assets();

        $this->container = apply_filters( 'plugin_press_get_container_class',$this->container );
    }

    /**
     * Localize our plugin
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function localization_setup()
    {

    }

    /**
     * Instantiate the plugin
     * 
     * @since 1.0.0
     * 
     * @return PluginPress
     * 
     */
    public static function instance()
    {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Determine request type
     * 
     * @since 1.0.0
     * 
     * @param string $type referes to the request type
     * 
     * @return bool
     */
    public function is_request( $type )
    {
        switch( $type ) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined('DOING_AJAX');
            case 'rest':
                return defined('REST_REQUEST');
            case 'corn':
                return defined('DOING_CORN');
            case 'fronted':
                return ( ! is_admin( ) || defined( 'DOING_AJAX' ) && ! defined( 'DOING_CORN' ) );
        }
    }
    /**
     * Define all constant
     * 
     * @return void
     * 
     * @since 1.0.0
     * 
     */
    public function define_constant()
    {
        define('PLUGIN_FILE',__FILE__);
        define('PLUGIN_PATH',__DIR__);
        define('PLUGIN_URL',plugins_url( '',PLUGIN_FILE ));
        define('PLUGIN_ASSETS_PATH',PLUGIN_PATH . '/assets');
        define('PLUGIN_INCLUDES_PATH',PLUGIN_PATH . '/includes');
        define('PLUGIN_TEMPLATE_PATH',PLUGIN_PATH . '/templates');
        define('TEXT_DOMAIN','plugin-press');
        define('PLUGIN_VERSION',self::VERSION);
    }
}

//Kick off the plugin
function plugin_press(){
    return PluginPress::instance();
}

plugin_press();