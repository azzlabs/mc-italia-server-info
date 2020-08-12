<?php function mcit_editor_page() { ?>

    <?php 
        $mcit_editor = new MCIT_editor();
        $mcit_editor->mcit_post_listener();
        $mcit_editor->mcit_load_yaml_file(ABSPATH . get_option('mcit_server_info_path'));
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
                    <td style="padding-bottom: 0">
                        <textarea class="mdeditor" name="page_content"><?php echo $mcit_editor->page_content; ?></textarea>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
<?php } ?>