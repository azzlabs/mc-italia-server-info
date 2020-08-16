<?php 
function mcit_history_page() { 
    global $mcit_history_list_table; ?>

    <div class="wrap">
        <h1 class="wp-heading-inline">Snapshots <em>server-info.yml</em></h1>
    
        <?php
            $mcit_history_list_table->display();
        ?>

        <a href="?page=mcit-server-info-editor" class="button-link inline"><?php echo __('Vai all\'editor', 'mcit') ?></a>

        <script>
            jQuery(document).ready(function($) {
                $('.editinline').click(function() {
                    var postid = $(this).data('postid');
                    var nonce = $(this).data('nonce');

                    $('#mcit_title_row_' + postid).hide(0, function() {
                        var title = $(this).find('.row-title').html();

                        $('#mcit_edit_row_' + postid).html(`
                            <input type="text" name="post_title" class="regular-text" value="${title}" placeholder="Nome snapshot">
                            <input type="hidden" name="snapshot_id" value="${postid}">
                            <input type="hidden" name="_wpnonce" value="${nonce}">
                            <button type="submit" class="button button-primary">Aggiorna</button> 
                            <button type="button" class="button-link closeinlineedit" data-postid="${postid}">Annulla</button>
                        `).show();

                        $('.closeinlineedit').click(function() {
                            var close_postid = $(this).data('postid');
                            $('#mcit_edit_row_' + close_postid).hide(0, function() { $('#mcit_title_row_' + postid).show() });
                        });
                    });
                });

                $('.deleterow').click(function(e) {
                    if ($(this).attr('href') == '#') {
                        e.preventDefault();
                        $(this).attr('href', $(this).data('href')).text('Conferma eliminazione');
                    } 
                });
            });
        </script>
    </div>
<?php } ?>