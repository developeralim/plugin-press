<?php
/**
 * @package Plugin-press function
 * @author Md Alim <developeralimcu@gmail.com>
 * @since 1.0.0
 */

/**
 * Load plugin template file
 * @param string $template_name 
 * @param mixed $args
 */
function load_plugin_template($template_name, $args = [])
{
    require_once PLUGIN_TEMPLATE_PATH . '/admin/'.$template_name.'.php';
}


//dumpping

function dd($data,$exit = false) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ( $exit ) {
        exit;
    }
}
