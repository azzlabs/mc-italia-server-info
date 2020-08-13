<?php function mcit_settings_page() { ?>
    <div class="wrap">
        <h1>Generatore <em>server-info.yml</em> per Minecraft-Italia.it</h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('mcit_settings_group'); ?>
            <?php do_settings_sections('mcit_settings_group'); ?>
            <?php $change_path = get_option('mcit_change_server_info'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php echo __('Percorso server-info.yml', 'mcit') ?></th>
                    <td>
                        <input type="text" name="mcit_server_info_path" value="/<?php echo esc_attr(get_option('mcit_server_info_path')); ?>" 
                            class="regular-text" <?php echo $change_path ? 'disabled' : ''; ?>/>
                        <p class="description">
                            <?php echo __('Modifica il percorso del file server-info.yml', 'mcit') ?>
                        </p>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><?php echo __('Ripristina posizione', 'mcit') ?></th>
                    <td>
                        <input type="checkbox" name="mcit_change_server_info" value="true" <?php echo $change_path ? 'checked' : ''; ?> />
                        <p class="description">
                            <?php echo __('Se selezionato, ripristina la posizione originale del file', 'mcit') ?>
                        </p>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row" colspan="2">

                    <?php $server_info_dirname = dirname(ABSPATH . get_option('mcit_server_info_path'));
                        if (!is_writable($server_info_dirname)) { ?>

                    
                        <?php echo sprintf(__('Attenzione! La cartella "%s" non esiste, oppure non ha i permessi di scrittura per wordpress', 'mcit'), $server_info_dirname) ?>
                    

                    <?php } else { ?>
                        <a href="?page=mcit-server-info-editor" class="button button-secondary"><?php echo __('Vai all\'editor', 'mcit'); ?></a>
                    <?php } ?>
                    </th>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        
        </form>
    </div>

    <script>
        jQuery('[name=mcit_change_server_info]').change(function() {
            jQuery('[name=mcit_server_info_path]').attr('disabled', jQuery(this).is(':checked'));
        });
    </script>
<?php } ?>