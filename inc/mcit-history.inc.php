<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class MCIT_History_List_Table extends WP_List_Table {
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort($data, [&$this, 'sort_data']);

        $perPage = 10;
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

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns() {
        $columns = [
            'ID'          => __('ID', 'mcit'),
            'post_title'  => __('Nome snapshot', 'mcit'),
            'date'        => __('Data snapshot', 'mcit'),
            'actions'     => __('Azioni', 'mcit')
        ];

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns() {
        return ['ID' => ['ID', false], 'post_title' => ['post_title', false]];
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data() {
        return get_posts(['post_type' => 'mcit_file_history', 'post_status' => 'all', 'numberposts' => -1]);
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Object $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name) {
        if ($column_name == 'ID') return '#' . $item->ID;
        if ($column_name == 'actions') return 'action #' . $item->ID;
        if ($column_name == 'date') return get_the_date('', $item->ID);
        return $item->$column_name;
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
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