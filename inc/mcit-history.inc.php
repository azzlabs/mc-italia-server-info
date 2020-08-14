<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class MCIT_History_List_Table extends WP_List_Table {
    public function prepare_items() {
        
        $this->mcit_process_actions();

        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort($data, [&$this, 'sort_data']);

        $perPage = 15;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args ([
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ]);

        $data = array_slice($data, (($currentPage-1) * $perPage), $perPage);

        $this->_column_headers = [$columns, $hidden, $sortable];
        $this->items = $data;
    }

    public function get_columns() {
        $columns = [
            'ID'          => __('ID', 'mcit'),
            'post_title'  => __('Nome snapshot', 'mcit'),
            'date'        => __('Data snapshot', 'mcit')
        ];

        return $columns;
    }

    public function get_hidden_columns() {
        return [];
    }

    public function get_sortable_columns() {
        return ['ID' => ['ID', false], 'post_title' => ['post_title', false]];
    }

    private function table_data() {
        return get_posts(['post_type' => 'mcit_file_history', 'post_status' => 'all', 'numberposts' => -1]);
    }

    public function column_default($item, $column_name) {
        if ($column_name == 'ID') return '#' . $item->ID;
        if ($column_name == 'post_title') {
            return sprintf('<a href="#" class="row-title">%s</a>%s', $item->post_title, $this->row_actions([
                'edit'   => sprintf('<button type="button" class="button-link editinline" aria-expanded="false">%s</button>', __('Modifica nome snapshot', 'mcit')),
                'delete' => sprintf('<a href="?page=%s&action=delete&snapshot_id=%s&_wpnonce=%s">%s</a>', esc_attr($_REQUEST['page']), absint($item->ID), wp_create_nonce('mcit_del_history_entry'), __('Elimina', 'mcit'))
            ]));
        }
        if ($column_name == 'date') return get_the_date('', $item->ID);
        return $item->$column_name;
    }

    public function mcit_process_actions() {
		// Detect when a bulk action is being triggered...
		if ('delete' === $this->current_action()) {

			// In our file that handles the request, verify the nonce.
			if (!wp_verify_nonce(esc_attr($_REQUEST['_wpnonce']), 'mcit_del_history_entry')) {
				die ('Something went wrong');
			} else {
				global $wpdb;

                $wpdb->delete(
                    "{$wpdb->prefix}posts",
                    ['ID' => $_GET['snapshot_id']],
                    ['%d']
                );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url
                wp_redirect(esc_url_raw(remove_query_arg('action')));
				exit;
			}
		}
    }

    private function sort_data($a, $b) {
        // Set defaults
        $orderby = 'post_title';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }

        $result = strcmp($a->$orderby, $b->$orderby);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }
}
?>