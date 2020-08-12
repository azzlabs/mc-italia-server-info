<?php function mcit_editor_page() { ?>

    <?php 
        $mcit_editor = new MCIT_editor();
        
        if (!empty($_POST)) {
            $yaml_file = '';
            foreach ($mcit_editor->server_info_fields as $field) {
                $yaml_file .= $mcit_editor->mcit_post_yaml_parser($field);
            }
            file_put_contents(ABSPATH . get_option('mcit_server_info_path'), $yaml_file);
        }

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
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>

    <script>
        var count = 0;

        jQuery(document).ready(function($) {

            $('.addSection').click(function(e) {
                e.preventDefault();
                addSection($(this), $(this).data('sectionslug'));
            });

            $('.color_field').each(function(){
                $(this).wpColorPicker();
            });

            $('[name*="-from"]').change(function() {
                const to = $(this).parent().find('[name*="-to"]');
                if (to.val() == '')
                    to.val($(this).val());
            });

            function addSection(parent, slug, content = '') {
                count++;
                var section_child = parent.parent().find('.parent-section');

                section_child.append(`<div class="${slug}-entry" style="margin-bottom: .5em">
                        <input type="text" name="${slug}[${count}]" class="regular-text" maxlength="50" value="${content}" placeholder="<?php echo __('Nome sezione', 'mcit') ?>" />
                        <button class="button button-secondary addEntry" data-sectionslug="${slug}_entry_${count}">Aggiungi ${slug}</button> 
                        <button class="button-link button-link-delete delSection"><?php echo __('Rimuovi sezione', 'mcit') ?></button>
                        <div class="section-entries section-entries-${count}"></div>
                    </div>`).data('section-id', count).data('sectionslug', slug + '_entry_' + count);
            
                $('.addEntry').unbind();
                $('.addEntry').click(function(e) {
                    e.preventDefault();

                    addEntry($(this), slug);
                });
                
                delEntryListener();
                return section_child;
            }
            
            function addEntry(parent, slug, content = '') {
                const section_slug = parent.data('sectionslug');
                var findclass = '.section-entries';
                if (parent.data('section-id')) findclass += '-' + parent.data('section-id');

                console.log(findclass);

                parent.parent().find(findclass).append(`<div class="${slug}-entry" style="margin-top: .5em; margin-left: 1.2em">
                        <input type="text" name="${section_slug}[]" value="${content}" class="regular-text" maxlength="50" placeholder="<?php echo __('Nome', 'mcit') ?> ${slug}" />
                        <button class="button-link button-link-delete delSection"><?php echo __('Rimuovi', 'mcit') ?></button>
                    </div>`);

                delEntryListener();
            }

            function delEntryListener() {
                $('.delSection').unbind();
                $('.delSection').click(function(e) {
                    e.preventDefault();
                    $(this).parent().remove();
                });
            }

            if (Array.isArray(staff_repeater_data)) {
                staff_repeater_data.forEach(arr => {
                    for (var key in arr) {
                        var child = addSection($('.addSection'), 'staff', key);
                        arr[key].forEach(val => {
                            addEntry(child, 'staff', val);
                        });
                    }
                });
            }
        });
    </script>
<?php } ?>