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

// AJAX endpoint
add_action('wp_ajax_mcit_get_uuid', 'mcit_get_uuid');
add_action('admin_menu', 'mcit_add_menu_entry');

function mcit_add_menu_entry() {
    // Registra la voce menu
    add_submenu_page('tools.php', 'MC-Italia server info', 'MC-Italia server info', 'administrator', 'mcit-server-info-generator', 'mcit_settings_page');
    add_submenu_page(null, 'MC-Italia server info editor', 'MC-Italia server info editor', 'administrator', 'mcit-server-info-editor', 'mcit_editor_page');
    add_submenu_page(null, 'MC-Italia server info editor', 'MC-Italia server info history', 'administrator', 'mcit-server-info-history', 'mcit_history_page');
    add_submenu_page(null, 'MC-Italia server info editor', 'MC-Italia server info preview', 'administrator', 'mcit-server-info-preview', 'mcit_preview_page');

    // Registra i campi delle impostazioni di WP
    add_action('admin_init', 'mcit_register_settings');

    // Aggiunge i custom script e stili
    add_action('admin_enqueue_scripts', 'mcit_widget_enqueue_scripts');

    // Aggiunge il cpt history
    register_post_type('mcit_file_history', ['public' => false]);

    // Gestione errori
    set_error_handler(function ($severity, $message, $file, $line) {
        throw new ErrorException($message, $severity, $severity, $file, $line);
    });
}

function mcit_widget_enqueue_scripts($hook) {
    if (!in_array($hook, [
        'tools_page_mcit-server-info-generator', 
        'tools_page_mcit-server-info-editor', 
        'tools_page_mcit-server-info-history',
        'tools_page_mcit-server-info-preview'
    ])) return;

    add_action('admin_head', 'mcit_custom_admin_js');

    if ($hook == 'tools_page_mcit-server-info-editor') {
        // Markdown editor
        wp_enqueue_style('simple_mde_css', plugin_dir_url(__FILE__) . 'assets/simple-mde/simplemde.min.css');
        wp_enqueue_script('simple_mde_script', plugin_dir_url(__FILE__) . 'assets/simple-mde/simplemde.min.js');
        wp_enqueue_script('mcit_smde_toolbar', plugin_dir_url(__FILE__) . 'assets/smde-toolbar.js');

        // Aggiunge il color picker come dipendenza
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    
        // Aggiunge il media dialog
        wp_enqueue_media();

        // Registra la classe per la pagina editor
        global $mcit_editor;
        $mcit_editor = new MCIT_editor();
        $mcit_editor->mcit_post_listener();
    }

    if ($hook == 'tools_page_mcit-server-info-preview') {
        // Code editor
        wp_enqueue_code_editor(['type' => 'text/html']);
    }

    // Stili e script custom
    wp_enqueue_script('mcit_edit_script', plugin_dir_url(__FILE__) . 'assets/editor-page-script.js');
    wp_enqueue_style('mcit_css', plugin_dir_url(__FILE__) . 'assets/mcit-style.css');

    // Rimpiazzo le dashicons per retrocompatibilità a WordPress < 5.5
	wp_enqueue_style('dashicons_css', plugin_dir_url(__FILE__) . 'assets/dashicons/css/dashicons.css');

    if ($hook == 'tools_page_mcit-server-info-history') {
        // Registra la classe per la pagina history
        global $mcit_history_list_table;
        $mcit_history_list_table = new MCIT_History_List_Table();
        $mcit_history_list_table->prepare_items();
    }
}

function mcit_custom_admin_js() { ?>
    <script>
        const mcit_locale = <?php echo json_encode(['label_name' => __('Nome', 'mcit'), 'label_remove' => __('Rimuovi', 'mcit'), 
                        'section_name' => __('Nome sezione', 'mcit'), 'section_remove' => __('Rimuovi sezione', 'mcit')]); ?>;
    </script>
<?php }

function mcit_get_uuid() {
    if (empty($_GET['username'])) return;
    if (!preg_match('/^[a-zA-Z0-9_]*$/', $_GET['username'])) return;
    $return = file_get_contents('https://api.mojang.com/users/profiles/minecraft/' . $_GET['username']);
    echo (empty($return) ? 'false' : $return);
    exit();
}

function mcit_register_settings() {
	register_setting('mcit_settings_group', 'mcit_server_info_path', ['type' => 'string', 'default' => MCIT_DEFPATH, 'sanitize_callback' => 'mcit_sanitize_path']);
	register_setting('mcit_settings_group', 'mcit_change_server_info', ['type' => 'boolean', 'default' => true, 'sanitize_callback' => 'mcit_sanitize_cb']);
}

function mcit_sanitize_path($string) {
    if ($_POST['mcit_change_server_info'] == 'true') return MCIT_DEFPATH;

    return empty($string) ? MCIT_DEFPATH : trim($string, '/');
}
function mcit_sanitize_cb($string) {
    return $string == 'true';
}

// Includo le dipendenze
foreach (glob(MCIT_ABSPATH . '/inc/*.inc.php') as $filename) {
    include_once $filename;
}