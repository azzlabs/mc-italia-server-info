<?php
/**
 * Plugin Name: Generatore server-info.yml
 * Plugin URI: https://github.com/azzlabs/mc-italia-server-info
 * Description: Genera il file server-info.yml per la bacheca server di Minecraft-Italia.it tramite wordpress
 * Version: 1.0
 * Author: azzlabs
 * Author URI: https://azzari.dev/
 */

// Strumentopolo misterioso, ci servirà più tardi ;)
define('MCIT_ABSPATH', dirname(__FILE__));
define('MCIT_DEFPATH', 'server-info.yml');

add_action('admin_menu', 'mcit_add_menu_entry');

function mcit_add_menu_entry() {
	// Registra la voce menu
	add_submenu_page('tools.php', 'MC-Italia server info', 'MC-Italia server info', 'administrator', 'mcit-server-info-generator', 'mcit_settings_page');
	add_submenu_page(null, 'MC-Italia server info editor', 'MC-Italia server info editor', 'administrator', 'mcit-server-info-editor', 'mcit_editor_page');

	// Registra i campi delle impostazioni di WP
    add_action('admin_init', 'mcit_register_settings');
    
    // Aggiunge il color picker come dipendenza
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    // Aggiunge i custom script e stili
    add_action('admin_enqueue_scripts', 'mcit_widget_enqueue_scripts');
}

function mcit_widget_enqueue_scripts() {   
    wp_enqueue_style('simple_mde_css', plugin_dir_url(__FILE__) . 'assets/simple-mde/simplemde.min.css');
    wp_enqueue_style('mcit_css', plugin_dir_url(__FILE__) . 'assets/mcit-style.css');
    wp_enqueue_script('simple_mde_script', plugin_dir_url(__FILE__) . 'assets/simple-mde/simplemde.min.js');
}

function mcit_register_settings() {
	register_setting('mcit_settings_group', 'mcit_server_info_path', ['type' => 'string', 'default' => MCIT_DEFPATH, 'sanitize_callback' => 'mcit_sanitize_path']);
	register_setting('mcit_settings_group', 'mcit_change_server_info', ['type' => 'boolean', 'default' => true]);
}

function mcit_sanitize_path($string) {
    if ($_POST['mcit_change_server_info'] == 'true') return MCIT_DEFPATH;

    return empty($string) ? MCIT_DEFPATH : trim($string, '/');
}

// Includo le dipendenze
foreach (glob(MCIT_ABSPATH . '/inc/*.inc.php') as $filename) {
    include_once $filename;
}