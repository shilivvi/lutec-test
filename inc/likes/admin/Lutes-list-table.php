<?php

class Lutes_List_Table extends WP_List_Table {
    function __construct(){
        parent::__construct([]);

        $this->prepare_items();
    }

    function prepare_items(){
        global $wpdb;

        $likes = $wpdb->get_results("SELECT l.post_id, COUNT(l.post_id) AS likes, p.post_title FROM {$wpdb->likes} l LEFT JOIN {$wpdb->posts} p ON p.ID = l.post_id WHERE l.like_value > 0 GROUP BY l.post_id", OBJECT_K);
        //$dislikes = $wpdb->get_results("SELECT l.post_id, COUNT(l.post_id) AS dislikes, p.post_title FROM {$wpdb->likes} l LEFT JOIN {$wpdb->posts} p ON p.ID = l.post_id WHERE l.like_value < 0 GROUP BY l.post_id", OBJECT_K);

        $this->items = $likes;

    }

    function get_columns(){
        return array(
            'post_title' => 'Имя записи',
            'likes' => 'Лайки',
        );
    }

    // вывод каждой ячейки таблицы...
    function column_default( $item, $colname ){

        if( $colname === 'customer_name' ){
            // ссылки действия над элементом
            $actions = array();
            $actions['edit'] = sprintf( '<a href="%s">%s</a>', '#', __('edit','hb-users') );

            return esc_html( $item->name ) . $this->row_actions( $actions );
        }
        else {
            return isset($item->$colname) ? $item->$colname : print_r($item, 1);
        }

    }
}