<?php
class MCIT_editor {
    public $categories;
    public $server_info_fields;
    public $current_dump;
    public $page_content;

    public function __construct() {
        $this->categories = [
            'creative', 'ctf', 'factions', 'hardcore', 'hungergames', 'minigames', 'moddati', 'pixelmon', 
            'prison', 'pvp', 'roleplay', 'skygames', 'survival', 'survivalgames', 'uhc', 'vanilla'
        ];

        $this->server_info_fields = [
            ['slug' => 'name', 'name' => __('Nome del server', 'mcit'), 'desc' => __('Non deve contenere decorazioni o testo che non sia parte integrante del nome del server', 'mcit'), 'type' => 'text', 'length' => '24'],
            ['slug' => 'url', 'name' => __('URL in Minecraft-Italia', 'mcit'), 'desc' => __('URL della pagina personalizzato', 'mcit'), 'type' => 'text', 'length' => '24'],
            ['slug' => 'hidden', 'name' => __('Nascosto', 'mcit'), 'desc' => __('Indica se il server deve essere nascosto temporaneamente dalla lista', 'mcit'), 'type' => 'checkbox', 'length' => ''],
            ['slug' => 'only-premium', 'name' => __('Solo premium', 'mcit'), 'desc' => __('Specifica se l\'accesso al server &egrave; riservato ai soli utenti possessori di una copia originale di Minecraft', 'mcit'), 'type' => 'checkbox', 'length' => ''],
            ['slug' => 'versions', 'name' => __('Versioni supportate', 'mcit'), 'desc' => __('Elenco di versioni supportate dal server di Minecraft.<br>Usare solo versioni principali (es. 1.15). Se ha solo una versione, ricopiare la stessa nel campo "a"', 'mcit'), 'type' => 'text-from-to', 'length' => '4'],
            ['slug' => 'categories', 'name' => __('Categorie', 'mcit'), 'desc' => __('Elenco di categorie di cui il server fa parte.<br>Ne vanno elencate un massimo di 5 e devono essere già attive al momento della modifica del file .yml<br>Suggerimento: usa ctrl + click per selezionarne più di una', 'mcit'), 'type' => 'select-multi', 'values' => $this->categories],
            ['slug' => 'staff', 'name' => __('Staff', 'mcit'), 'desc' => __('Elenco di ruoli, contenenti a loro volta un elenco di Minecraft UUID relativi agli staffer del server.<br>Se non si vuole la scheda "Staff" basta lasciare il campo vuoto', 'mcit'), 'type' => 'repeater', 'length' => '36'],
            ['slug' => 'www', 'name' => __('Sito web', 'mcit'), 'desc' => __('Indirizzo completo del sito web. Va preceduto da http:// o https://', 'mcit'), 'type' => 'text', 'length' => '48'],
            ['slug' => 'forum', 'name' => __('Forum', 'mcit'), 'desc' => __('Indirizzo completo del forum. Va preceduto da http:// o https://', 'mcit'), 'type' => 'text', 'length' => '48'],
            ['slug' => 'telegram', 'name' => __('Telegram', 'mcit'), 'desc' => __('Invito al canale Telegram. Va preceduto da http:// o https://', 'mcit'), 'type' => 'text', 'length' => '32'],
            ['slug' => 'discord', 'name' => __('Discord', 'mcit'), 'desc' => __('Invito al server Discord. Va preceduto da http:// o https://', 'mcit'), 'type' => 'text', 'length' => '32'],
            ['slug' => 'twitter', 'name' => __('Twitter', 'mcit'), 'desc' => __('Username del profilo di Twitter', 'mcit'), 'type' => 'text', 'length' => '15'],
            ['slug' => 'instagram', 'name' => __('Instagram', 'mcit'), 'desc' => __('Username del profilo di Instagram', 'mcit'), 'type' => 'text', 'length' => '30'],
            ['slug' => 'teamspeak', 'name' => __('TeamSpeak', 'mcit'), 'desc' => __('Indirizzo del server TeamSpeak', 'mcit'), 'type' => 'text', 'length' => '48'],
            ['slug' => 'mumble', 'name' => __('Mumble', 'mcit'), 'desc' => __('Indirizzo del server Mumble', 'mcit'), 'type' => 'text', 'length' => '48'],
            ['slug' => 'query_port', 'name' => __('Query port', 'mcit'), 'desc' => __('Indica se, per questioni tecniche, la server query va eseguita su una porta specifica', 'mcit'), 'type' => 'number', 'length' => ''],
            ['slug' => 'theme_color', 'name' => __('Colore del tema', 'mcit'), 'desc' => __('Colore in esadecimale, indica il colore del tema della pagina in lista server', 'mcit'), 'type' => 'color-field', 'length' => '']
        ];
    }

    static public function mcit_target_folder() {
        $server_info_dirname = dirname(ABSPATH . get_option('mcit_server_info_path'));
        return (file_exists($server_info_dirname) ? $server_info_dirname : false);
    }
    
    static public function mcit_target_file() {
        $server_info_file = ABSPATH . get_option('mcit_server_info_path');
        return (file_exists($server_info_file) ? $server_info_file : false);
    }

    static public function mcit_target_folder_writable() {
        $server_info_dirname = dirname(ABSPATH . get_option('mcit_server_info_path'));
        return (is_writable($server_info_dirname) ? $server_info_dirname : false);
    }
    
    static public function mcit_target_file_writable() {
        $server_info_file = ABSPATH . get_option('mcit_server_info_path');
        return (is_writable($server_info_file) ? $server_info_file : false);
    }

    static public function mcit_writable_test($top_msg = false) {
        if (!$top_msg) 
            $top_msg = __('Errore', 'mcit');

        if (!self::mcit_target_folder()) {
            self::mcit_print_error($top_msg, __('La cartella di destinazione non esiste.<br>&Egrave; necessario creare la cartella e assegnare i permessi di scrittura prima di procedere'));
            return false;
        }
        if (!self::mcit_target_folder_writable()) {
            self::mcit_print_error($top_msg, __('La cartella di destinazione non &egrave; scrivibile da WordPress.<br>&Egrave; necessario creare la cartella e assegnare i permessi di scrittura prima di procedere'));
            return false;
        }
        if (self::mcit_target_file() && !self::mcit_target_file_writable()) {
            self::mcit_print_error($top_msg, __('Il file di destinazione &egrave; già presente ma non &egrave; scrivibile da WordPress.<br>&Egrave; necessario assegnare al file i permessi di scrittura prima di procedere'));
            return false;
        }

        return true;
    }

    static public function mcit_read_yaml_file() {
        $content = file_get_contents(ABSPATH . get_option('mcit_server_info_path'));
        if ($content === false) {
            self::mcit_print_error(__('Errore nell\'apertura del file server-info', 'mcit'));
            return false;
        }
        return $content;
    }

    static public function mcit_write_yaml_file($yaml_file) {
        if (self::mcit_writable_test()) {
            try {
                if (file_put_contents(ABSPATH . get_option('mcit_server_info_path'), str_replace("\r\n", "\n", $yaml_file)))
                    self::mcit_print_error(__('File server-info salvato con successo', 'mcit'), '', 'updated', true);
            } catch (Exception $e) {
                self::mcit_print_error(__('Errore nel salvataggio del file server-info', 'mcit'), $e->getMessage());
            }
        }
    }

    static public function mcit_preview_post_listener() {
        // Modifica snapshot
        if (isset($_POST['mcit_submit_snapshot'])) {
            try {
                wp_update_post(['ID' => $_POST['snapshot_id'], 'post_content' => $_POST['snapshot_content']]);
                self::mcit_print_error(__('Snapshot aggiornato con successo', 'mcit'), '', 'updated', true);
            } catch (Exception $e) {
                self::mcit_print_error(__('Errore nell\'aggiornamento dello snapshot', 'mcit'), $e->getMessage());
            }
        } 

        // Modifica snapshot e carica in loader
        elseif (isset($_POST['mcit_submit_load'])) {
            try {
                wp_update_post(['ID' => $_POST['snapshot_id'], 'post_content' => $_POST['snapshot_content']]);
                wp_redirect(esc_url_raw(add_query_arg(['page' => 'mcit-server-info-editor', 'snapshot_id' => $_POST['snapshot_id']])));
                die();
            } catch (Exception $e) {
                self::mcit_print_error(__('Errore nell\'aggiornamento dello snapshot', 'mcit'), $e->getMessage());
            }
        } 
        // Salva nel file yml

        elseif (isset($_POST['mcit_submit_file']) && !empty($_POST['snapshot_content'])) {
            self::mcit_write_yaml_file(stripcslashes($_POST['snapshot_content']));
        }
    }

    public function mcit_load_yaml($content = false) {
        try {
            if (empty($content)) $content = $this->mcit_read_yaml_file();
            
            $content = explode("\n---\n", $content, 3);

            $this->current_dump = Symfony\Component\Yaml\Yaml::parse($content[0]);
            $this->page_content = $content[1];
        } catch (Exception $e) {
            $this->mcit_print_error(__('Errore nella lettura del file server-info', 'mcit'), $e->getMessage());
            $this->current_dump = [];
        }
    }

    static function mcit_print_error($error, $desc = '', $class = 'error', $dismissable = false) {
        if (!empty($desc)) $desc = '<br>' . $desc;
        printf('<div id="mcit_message" class="%s"><p><strong>%s</strong>%s</p>%s</div>', $class . ($dismissable ? ' notice is-dismissable' : ''), $error, $desc, 
            ($dismissable ? '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Ignora questa notifica.</span></button>' : ''));
    }

    public function mcit_post_listener() {
        if (!empty($_POST)) {
            $yaml_file = "---\n";
            foreach ($this->server_info_fields as $field) {
                $yaml_file .= $this->mcit_yaml_line_dumper($field);
            }
            $yaml_file .= "---\n";
            $yaml_file .= stripcslashes($_POST['page_content']);
            $yaml_file .= "\n---\n";

            if ((isset($_POST['mcit_save_snapshot']) && $_POST['mcit_save_snapshot'] == 'true') || isset($_POST['mcit_preview'])) {
                $snap_name = sprintf('Snapshot %s %s (%s %s)', __('del', 'mcit'), date('d/m/Y, H:i'), $_POST['name'], $_POST['versions-to']);
                $snap_id = wp_insert_post(['post_title' => $snap_name, 'post_content' => $yaml_file, 'post_type' => 'mcit_file_history']);

                if (isset($_POST['mcit_preview'])) {
                    wp_redirect(esc_url_raw(add_query_arg(['page' => 'mcit-server-info-preview', 'snapshot_id' => $snap_id])));
                    die();
                }
            }

            if (isset($_POST['mcit_submit'])) {
                $this->mcit_write_yaml_file($yaml_file);
            }
        }
    }

    public function mcit_yaml_line_dumper($field) {
        if (!empty($_POST[$field['slug']]) || (!empty($_POST[$field['slug'] . '-from']) && !empty($_POST[$field['slug'] . '-to']))) {
            switch ($field['type']) {
                case 'text-from-to':
                    $return_val = $field['slug'] . ":\n";
                    $n_from = (int)substr($_POST[$field['slug'] . '-from'], 2);
                    $n_to = (int)substr($_POST[$field['slug'] . '-to'], 2);
                    for ($i = $n_from; $i <= $n_to; $i++) {
                        $return_val .= sprintf("- \"%s\"\n", substr($_POST[$field['slug'] . '-from'], 0, 2) . $i);
                    }
                    return $return_val;
                case 'select-multi': 
                    $return_val = $field['slug'] . ":\n";
                    foreach ($_POST[$field['slug']] as $entry_value) {
                        $return_val .= sprintf("- \"%s\"\n", $entry_value);
                    }
                    return $return_val;
                case 'repeater':
                    $return_val = $field['slug'] . ":\n";
                    foreach ($_POST[$field['slug']] as $key => $entry_label) {
                        $return_val .= sprintf("- %s:\n", $entry_label);

                        $values_slug = $field['slug'] . '_entry_' . $key;
                        if (!empty($_POST[$values_slug])){
                            foreach ($_POST[$values_slug] as $entry_value) {
                                $return_val .= sprintf("  - \"%s\"\n", $entry_value);
                            }
                        }
                    }
                    return $return_val;
                case 'checkbox': return sprintf("%s: %s\n", $field['slug'], str_replace('"', '\"', $_POST[$field['slug']]));
                case 'number': return sprintf("%s: %d\n", $field['slug'], str_replace('"', '\"', (int)$_POST[$field['slug']]));
                default: return sprintf("%s: \"%s\"\n", $field['slug'], str_replace('"', '\"', $_POST[$field['slug']]));
            }
        }
    }

    public function mcit_parse_server_info_field($field) {
        switch ($field['type']) {
            case 'repeater':
                return sprintf('<div class="parent-section"></div>
                    <button class="button button-secondary addSection" data-sectionslug="%s">Aggiungi sezione</button>
                    <script>var %s_repeater_data = %s;</script>', 
                    $field['slug'], $field['slug'], json_encode($this->mcit_get_cur_value($field), JSON_PRETTY_PRINT));
                break;
            case 'text-from-to': return sprintf('%s <input type="text" name="%s-from" maxlength="%s" value="%s"/> %s <input type="text" name="%s-to" maxlength="%s" value="%s"/>', 
                __('da', 'mcit'), $field['slug'], $field['length'], $this->mcit_get_cur_value($field, '', true), __('a', 'mcit'), $field['slug'], $field['length'], $this->mcit_get_cur_value($field, '', false)); break;
            case 'select-multi': 
                $sel = sprintf('<select name="%s[]" class="regular-text" multiple>', $field['slug']);
                foreach ($field['values'] as $opt) {
                    $sel.= sprintf('<option value="%s" %s>%s</option>', $opt, 
                        in_array($opt, $this->mcit_get_cur_value($field, [])) ? 'selected' : '', ucfirst($opt));
                }
                return $sel . '</select>';
                break;
            case 'color-field': return sprintf('<input class="color_field" type="text" name="%s" style="margin-right: .5em" value="%s"/>', $field['slug'], $this->mcit_get_cur_value($field)); break;
            case 'checkbox': return sprintf('<input type="hidden" name="%s" value="false"/><input type="checkbox" name="%s" value="true" %s/>', $field['slug'], $field['slug'], $this->mcit_get_cur_value($field, false) ? 'checked' : '');
            default: return sprintf('<input type="%s" name="%s" class="regular-text" maxlength="%s" value="%s"/>', $field['type'], $field['slug'], $field['length'], $this->mcit_get_cur_value($field));
        }
    }

    public function mcit_get_cur_value($field, $default = '', $fromto = null) {
        $current_dump = $this->current_dump;

        if (!empty($current_dump[$field['slug']]) || (!empty($current_dump[$field['slug'] . '-from']) && !empty($current_dump[$field['slug'] . '-to']))) {
            if ($fromto !== null) return $fromto ? $current_dump[$field['slug']][0] : $current_dump[$field['slug']][count($current_dump[$field['slug']]) - 1];
            return $current_dump[$field['slug']];
        } else return $default;
    }
}