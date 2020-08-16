<?php function mcit_preview_page() { ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Preview <em>server-info.yml</em></h1>

        <?php 
            $file_contents = '';
            $snap_mode = false;
        
            if (isset($_GET['snapshot_id'])) {
                $snapshot = get_post($_GET['snapshot_id']);

                if ($snapshot) {
                    $file_contents = $snapshot->post_content;
                    $snap_mode = true;
                ?>
                    <h2><?php echo sprintf('%s "%s"', __('Stai visualizzando', 'mcit'), $snapshot->post_title); ?></h2>
                <?php } else MCIT_editor::mcit_print_error(__('Lo sanpshot richiesto non esiste', 'mcit')); ?>

            <?php } else { 
                    $file_contents = MCIT_editor::mcit_read_yaml_file();
                ?>
                    <h2><?php echo __('Stai visualizzando il contenuto corrente del file server-info.yml', 'mcit') ?></h2>
            <?php } ?>

        <form method="POST">
            <div class="mcit-code-editor-container">
                <textarea id="mcit_wp_code_editor" rows="5" name="snapshot_content"><?php echo $file_contents; ?></textarea>
            </div>

            <p class="submit">
                <?php if ($snap_mode) { ?>
                <input type="hidden" name="snapshot_id" value="<?php echo $_GET['snapshot_id']; ?>">
                <input type="submit" name="mcit_submit_snapshot" class="button button-primary" value="<?php echo __('Salva le modifiche allo snapshot', 'mcit') ?>">
                <input type="submit" name="mcit_submit_load" class="button" value="<?php echo __('Salva snapshot e carica nell\'editor', 'mcit') ?>">
                <?php } else { ?>
                <input type="submit" name="mcit_submit_file" class="button button-primary" value="<?php echo __('Salva modifiche nel file', 'mcit') ?>">
                <?php } ?>
                <a href="?page=mcit-server-info-editor" class="button-link inline"><?php echo __('Vai all\'editor', 'mcit') ?></a>
            </p>
        </form>

        <script>
            jQuery(document).ready(function($) {
                if ($('#mcit_wp_code_editor').length) {
                    var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
                    editorSettings.codemirror = _.extend(
                        {},
                        editorSettings.codemirror,
                        {
                            indentUnit: 2,
                            tabSize: 2,
                            lineSeparator: '\n',
                            mode: "text/x-yaml",
                        }
                    );
                    var editor = wp.codeEditor.initialize($('#mcit_wp_code_editor'), editorSettings);
                }
            });
        </script>
    </div>
<?php } ?>