<?php
    /**
     * Plugin press menu class
     * 
     * @package Menu
     * 
     * @since 1.0.0
     * 
     * @author Md Alim <developeralimcu@gmail.com>
     */
    namespace PluginPress\admin;

    class Menu {
        /**
         * Construct Ajax class and register ajax action
         * @return void
         * @since 1.0.0
         *
         */

        public function __construct()
        {
            add_action('admin_menu',[$this,'admin_menu']);
        }

        /**
         * Regsiter admin menu & sub menu
         * @return void
         * @since 1.0.0
         */

        public function admin_menu()
        {
            foreach ($this->get_menu_pages() as $handle => $page) {
               
                $page_option = $page['options'] ?? false;
                $sub_pages   = $page['subpage'] ?? [];
                $capability   = $page_option['capability'] ?? 'manage_options';

                if ( ! $page_option ) continue;

                $hook = add_menu_page( 
                    __($page_option['page_title'] ?? $handle,TEXT_DOMAIN),
                    __($page_option['menu_title'] ?? $handle,TEXT_DOMAIN),
                    $capability,
                    __($handle,TEXT_DOMAIN),
                    function() use($handle){
                        $this->load_template($handle);
                    },
                    $page_option['icon_url'] ?? '', 
                    $page_option['position'] ?? 10
                );

                if ( current_user_can( $capability ) ) {

                    foreach( $sub_pages as $sub_page ) {
                        add_submenu_page( 
                            __($handle,TEXT_DOMAIN), 
                            __($sub_page['page_title'],TEXT_DOMAIN), 
                            __($sub_page['menu_title'],TEXT_DOMAIN), 
                            $capability, 
                            $path = __($sub_page['menu_slug'],TEXT_DOMAIN), 
                            function() use($path){
                                $this->load_template($path);
                            }                      
                        );
                    }
                }

                add_action( "load-$hook",[$this,'init_hooks'] );
            }

            
        }
        
        /**
         * Load all actions and hooks when admin page triggers on
         * @return void
         * @since 1.0.0
         */
        public function init_hooks()
        {
            add_action( 'admin_enqueue_scripts',[$this,'enqueue_admin_scripts'] );
        }

        /**
         * Enqueue all admin css and js
         * @return void
         * @since 1.0.0
         */
        public function enqueue_admin_scripts()
        {
            wp_enqueue_script( 'plugin-press-admin-script' );
            wp_enqueue_style( 'plugin-press-admin-css' );
        }

        /**
         * enlist all admin pages and sub pages to an array
         * @return array 
         * @since 1.0.0
         */
        public function get_menu_pages()
        {
            return apply_filters('admin_menu_pages',[
                'test' => [
                    'options' => [
                        'page_title'    => 'Page Title',
                        'menu_title'    => 'Menu Title',
                        'capability'    => 'manage_options',
                        'icon_url'      => '',
                        'position'      => 10,
                    ]
                ]
            ]);
        }

        /**
         * Load template page for menu
         * @since 1.0.0
         * @param string $template_name
         */
        public function load_template( $template_name = '',$args = [] )
        {
            $view = PLUGIN_TEMPLATE_PATH . '/admin/' . $template_name . '.php';

            $data = apply_filters( "loading_{$template_name}_page_template",compact('view','args'));

            //extract data
            extract($data);

            if ( ! file_exists($view) ) {
                printf('<code>%s</code>file not exist',$view);
                return;
            } 

            // before template rendering action
            do_action('before_plugin_template_render',$data);

            if ( is_array($args) ) {
                foreach ($args as $key => $value) {
                    $$key = $value;
                }    
            }

            require_once $view;

            do_action('after_plugin_template_render',$data);
        }
    }