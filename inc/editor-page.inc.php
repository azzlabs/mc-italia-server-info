<?php function mcit_editor_page() { ?>

    <pre><?php 
        $mcit_editor = new MCIT_editor();
        $mcit_editor->mcit_load_yaml_file(ABSPATH . get_option('mcit_server_info_path'));

        print_r($mcit_editor->current_dump);
        
        if (!empty($_POST)) {
            $yaml_file = '';

            foreach ($mcit_editor->server_info_fields as $field) {
                $yaml_file .= $mcit_editor->mcit_post_yaml_parser($field);
            }

            file_put_contents(ABSPATH . get_option('mcit_server_info_path'), $yaml_file);
        }

    ?></pre>

    <?php
        
        
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
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>

    <script>
        jQuery(document).ready(function($) {
            var count = 0;

            $('.addSection').click(function(e) {
                const slug = $(this).data('sectionslug');
                count++;

                e.preventDefault();
                $(this).parent().find('.parent-section').append(`<div class="${slug}-entry" style="margin-bottom: .5em">
                        <input type="text" name="${slug}[${count}]" class="regular-text" maxlength="50" placeholder="<?php echo __('Nome sezione', 'mcit') ?>" />
                        <button class="button button-secondary addEntry" data-sectionslug="${slug}_entry_${count}">Aggiungi ${slug}</button> 
                        <button class="button-link button-link-delete delSection"><?php echo __('Rimuovi sezione', 'mcit') ?></button>
                        <div class="section-entries"></div>
                    </div>`);
            
                $('.addEntry').unbind();
                $('.addEntry').click(function(e) {
                    const section_slug = $(this).data('sectionslug');

                    e.preventDefault();
                    $(this).parent().find('.section-entries').append(`<div class="${slug}-entry" style="margin-top: .5em; margin-left: 1.2em">
                            <input type="text" name="${section_slug}[]" class="regular-text" maxlength="50" placeholder="<?php echo __('Nome', 'mcit') ?> ${slug}" />
                            <button class="button-link button-link-delete delSection"><?php echo __('Rimuovi', 'mcit') ?></button>
                        </div>`);

                    $('.delSection').unbind();
                    $('.delSection').click(function(e) {
                        e.preventDefault();
                        $(this).parent().remove();
                    });
                });
                
                $('.delSection').unbind();
                $('.delSection').click(function(e) {
                    e.preventDefault();
                    $(this).parent().remove();
                });
            });

            $('.color_field').each(function(){
                $(this).wpColorPicker();
            });
        });
    </script>
<?php } ?>