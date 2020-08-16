<?php function mcit_settings_page() { ?>
    <div class="wrap">
        <h1>Generatore <em>server-info.yml</em> per Minecraft-Italia.it</h1>
        
        <?php 
            try {
                $new_ver = file_get_contents('https://raw.githubusercontent.com/azzlabs/mc-italia-server-info/master/update_ver.txt');
                $cur_ver = file_get_contents(MCIT_ABSPATH . '/update_ver.txt');
            } catch (Exception $e) {
                MCIT_editor::mcit_print_error(__('Non sono riuscito a controllare gli aggiornamenti', 'mcit'), $e->getMessage());
            }

            if (!empty($new_ver) && !empty($cur_ver) && (floatval($new_ver) > floatval($cur_ver))) {
                MCIT_editor::mcit_print_error(__('Trovato nuovo aggiornamento!', 'mcit'), 
                    sprintf(__('Puoi ottenerlo dal repository ufficiale. %sClicca qui%s per leggere le istruzioni e scaricarlo!'),
                    '<a href="https://github.com/azzlabs/mc-italia-server-info" target="_blank">', '</a>'), 'updated');
            }
        ?>

        <form method="post" action="options.php">
            <?php settings_fields('mcit_settings_group'); ?>
            <?php do_settings_sections('mcit_settings_group'); ?>
            <?php $mcit_change_path = get_option('mcit_change_server_info'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo __('Percorso server-info.yml', 'mcit') ?></th>
                    <td>
                        <input type="text" name="mcit_server_info_path" value="/<?php echo esc_attr(get_option('mcit_server_info_path')); ?>" 
                            class="regular-text" <?php echo $mcit_change_path ? 'disabled' : ''; ?>/>
                        <p class="description">
                            <?php echo __('Modifica il percorso del file server-info.yml', 'mcit') ?>
                        </p>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><?php echo __('Ripristina posizione', 'mcit') ?></th>
                    <td>
                        <input type="hidden" name="mcit_change_server_info" value="false">
                        <input type="checkbox" name="mcit_change_server_info" id="mcit_cbinfo" value="true" <?php echo $mcit_change_path ? 'checked' : ''; ?> />
                        <label for="mcit_cbinfo">
                            <?php echo __('Ripristina la posizione originale del file', 'mcit') ?>
                        </label>
                    </td>
                </tr>
                
                <?php if (WP_DEBUG) { ?>
                <tr valign="top">
                    <th scope="row"><?php echo __('Stato', 'mcit') ?></th>
                    <td>
                        <p>
                            Cartella: <?php echo MCIT_editor::mcit_target_folder() ? 
                                '<span class="mcit-status green">Esiste</span>' : '<span class="mcit-status red">Da generare</span>'; ?>
                            <?php echo MCIT_editor::mcit_target_folder_writable() ? '' : ', <span class="mcit-status red">Non scrivibile</span>'; ?><br> 
                            File: <?php echo MCIT_editor::mcit_target_file() ? 
                                '<span class="mcit-status green">Esiste</span>' : '<span class="mcit-status red">Da generare</span>'; ?>
                            <?php echo MCIT_editor::mcit_target_file_writable() ? '' : ', <span class="mcit-status red">Non scrivibile</span>'; ?>
                        </p>
                    </td>
                </tr>
                <?php } ?>

                <?php if (MCIT_editor::mcit_writable_test()) { ?>
                <tr valign="top">
                    <th scope="row"><?php echo __('Modifica server-info.yml', 'mcit') ?></th>
                    <td>
                        <a href="?page=mcit-server-info-editor" class="button button-secondary"><?php echo __('Vai all\'editor', 'mcit'); ?></a>
                    </td>
                </tr>
                <?php } ?>

                <tr valign="top">
                    <th scope="row"><?php echo __('Snapshot', 'mcit') ?></th>
                    <td>
                        <a href="?page=mcit-server-info-history" class="button button-secondary"><?php echo __('Vai agli snapshot', 'mcit'); ?></a>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php echo __('Editor del codice', 'mcit') ?></th>
                    <td>
                        <a href="?page=mcit-server-info-preview" class="button button-secondary"><?php echo __('Mostra anteprima file', 'mcit'); ?></a>
                        <a href="<?php echo site_url(get_option('mcit_server_info_path')); ?>"  class="button-link inline" target="_blank">
                            <?php echo __('Mostra il file "live"', 'mcit') ?><i class="dashicons dashicons-external"></i></a>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__('Salva la configurazione', 'mcit')); ?>
        
        </form>
    </div>

    <script>
        jQuery('[name=mcit_change_server_info]').change(function() {
            jQuery('[name=mcit_server_info_path]').attr('disabled', jQuery(this).is(':checked'));
        });
    </script>
<?php } ?>