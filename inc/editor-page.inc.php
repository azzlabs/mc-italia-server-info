<?php function mcit_editor_page() { ?>

    <?php 
        global $mcit_editor;
        $yaml_content = false;

        if (isset($_GET['snapshot_id'])) {
            $snapshot = get_post($_GET['snapshot_id']);

            if ($snapshot) {
                $yaml_content = $snapshot->post_content;
                MCIT_editor::mcit_print_error(sprintf('"%s" %s', $snapshot->post_title, __('caricato correttamente')), '', 'updated');
            }
        }

        $mcit_editor->mcit_load_yaml($yaml_content);
    ?>

    <div class="wrap">
        <h1>Editor <em>server-info.yml</em> per Minecraft-Italia.it</h1>
        
        <form method="post">
            <table class="form-table">
                
                <?php foreach($mcit_editor->server_info_fields as $info_field) { ?>
                <tr valign="top">
                    <th scope="row"><?php echo $info_field['name'] ?></th>
                    <td style="padding-bottom: 0">
                        <?php echo $mcit_editor->mcit_parse_server_info_field($info_field) ?>
                        <p class="description">
                            <?php echo $info_field['desc'] ?>
                        </p>
                    </td>
                </tr>
                
                <?php } ?>

                <tr valign="top">
                    <th scope="row"><?php echo __('Contenuto della pagina', 'mcit') ?></th>
                    <td>
                        <textarea class="mdeditor" name="page_content"><?php echo $mcit_editor->page_content; ?></textarea>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row"><?php echo __('Salva snapshot', 'mcit') ?></th>
                    <td>
                        <input type="hidden" name="mcit_save_snapshot" value="false">
                        <input type="checkbox" name="mcit_save_snapshot" id="mcit_cbsnapshot" value="true" checked="">
                        <label for="mcit_cbsnapshot"><?php echo __('Salva uno snapshot nel database di WordPress', 'mcit') ?></label>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="mcit_submit" id="mcit_submit" class="button button-primary" value="<?php echo __('Salva modifiche nel file', 'mcit') ?>">
                <input type="submit" name="mcit_preview" id="mcit_preview" class="button" value="<?php echo __('Mostra anteprima', 'mcit') ?>">
                <a href="?page=mcit-server-info-history" class="button-link inline"><?php echo __('Vai agli snapshot salvati', 'mcit') ?></a>
            </p>
        </form>
    </div>
<?php } ?>