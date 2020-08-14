<?php 
function mcit_history_page() { 

    ?>
    <div class="wrap">
        <h1>History <em>server-info.yml</em></h1>
    
        <?php    
            // var_dump(wp_insert_post(['post_title' => 'Snapshot del ' . date('d/m/Y H:i:s'), 'post_type' => 'mcit_file_history']));

            $exampleListTable = new MCIT_History_List_Table();
            $exampleListTable->prepare_items();
            $exampleListTable->display();
        ?>
    </div>
<?php } ?>