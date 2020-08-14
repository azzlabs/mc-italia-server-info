<?php 
function mcit_history_page() { 
    global $mcit_history_list_table;
    ?>
    <div class="wrap">
        <h1>History <em>server-info.yml</em></h1>
    
        <?php    
            // var_dump(wp_insert_post(['post_title' => 'Snapshot del ' . date('d/m/Y H:i:s'), 'post_type' => 'mcit_file_history']));

            $mcit_history_list_table->display();
        ?>
    </div>
<?php } ?>