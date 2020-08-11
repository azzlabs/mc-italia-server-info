<?php function mcit_editor_page() { ?>

    <?php

        $categories = [
            'creative', 'ctf', 'factions', 'hardcore', 'hungergames', 'minigames', 'moddati', 'pixelmon', 
            'prison', 'pvp', 'roleplay', 'skygames', 'survival', 'survivalgames', 'uhc', 'vanilla'
        ];

        $server_info_fields = [
                ['slug' => 'name', 'name' => __('Nome del server', 'mcit'), 'desc' => __('Non deve contenere decorazioni o testo che non sia parte integrante del nome del server', 'mcit'), 'type' => 'text', 'length' => '24'],
                ['slug' => 'url', 'name' => __('URL in Minecraft-Italia', 'mcit'), 'desc' => __('URL della pagina personalizzato', 'mcit'), 'type' => 'text', 'length' => '24'],
                ['slug' => 'hidden', 'name' => __('Nascosto', 'mcit'), 'desc' => __('Indica se il server deve essere nascosto temporaneamente dalla lista', 'mcit'), 'type' => 'checkbox', 'length' => ''],
                ['slug' => 'only-premium', 'name' => __('Solo premium', 'mcit'), 'desc' => __('Specifica se l\'accesso al server è riservato ai soli utenti possessori di una copia originale di Minecraft', 'mcit'), 'type' => 'checkbox', 'length' => ''],
                ['slug' => 'versions', 'name' => __('Versioni supportate', 'mcit'), 'desc' => __('Elenco di versioni supportate dal server di Minecraft.<br>Usare solo versioni principali (es. 1.15). Se ha solo una versione, lasciare vuoto il campo "a"', 'mcit'), 'type' => 'text-from-to', 'length' => '4'],
                ['slug' => 'categories', 'name' => __('Categorie', 'mcit'), 'desc' => __('Elenco di categorie di cui il server fa parte.<br>Ne vanno elencate un massimo di 5 e devono essere già attive al momento della modifica del file .yml<br>Suggerimento: usa ctrl + click per selezionarne più di una', 'mcit'), 'type' => 'select-multi', 'values' => $categories],
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
                ['slug' => 'theme_color', 'name' => __('Colore del tema', 'mcit'), 'desc' => __('Colore in esadecimale, indica il colore del tema della pagina in lista server', 'mcit'), 'type' => 'text', 'length' => '']
            ];
        
        function mcit_parse_server_info_field($field) {
            switch ($field['type']) {
                case 'repeater':
                    return sprintf('<input type="text" name="%s" class="regular-text" maxlength="%s"/>
                        <button class="button button-secondary">Aggiungi membro staff</button><br><br>
                        <button class="button button-secondary">Aggiungi sezione staff</button>', $field['slug'], $field['length']);
                    break;
                case 'text-from-to': return sprintf('%s <input type="text" name="%s-from" maxlength="%s"/> %s <input type="text" name="%s-to" maxlength="%s"/>', 
                    __('da', 'mcit'), $field['slug'], $field['length'], __('a', 'mcit'), $field['slug'], $field['length']); break;
                case 'select-multi': 
                    $sel = sprintf('<select name="%s" class="regular-text" multiple>', $field['slug']);
                    foreach ($field['values'] as $opt) {
                        $sel.= sprintf('<option value="%s">%s</option>', $opt, ucfirst($opt));
                    }
                    return $sel . '</select>';
                    break;
                default: return sprintf('<input type="%s" name="%s" class="regular-text" maxlength="%s"/>', $field['type'], $field['slug'], $field['length']);
            }
        }
    ?>

    <div class="wrap">
        <h1>Editor <em>server-info.yml</em> per Minecraft-Italia.it</h1>
        
        <form method="post" action="options.php">
            <table class="form-table">
                
                <?php foreach($server_info_fields as $info_field) { ?>
                <tr valign="top">
                    <th scope="row"><?php echo $info_field['name'] ?></th>
                    <td style="padding-bottom: 0">
                        <?php echo mcit_parse_server_info_field($info_field) ?>
                        <p class="description">
                            <?php echo $info_field['desc'] ?>
                        </p>
                    </td>
                </tr>
                
                <?php } ?>
            </table>
                    
        </form>
    </div>

    <script>
        jQuery('[name=mcit_change_server_info]').change(function() {
            jQuery('[name=mcit_server_info_path]').attr('disabled', jQuery(this).is(':checked'));
        });
    </script>
<?php } ?>