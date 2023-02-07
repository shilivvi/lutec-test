<?php

class Lutec_Likes
{
    function __construct()
    {
        add_action('init', [$this, 'init']);

        add_shortcode('likes', [$this, 'template']);

        add_action('wp_ajax_lutec_likes', [$this, 'lutec_likes_callback']);
        add_action('wp_ajax_nopriv_lutec_likes', [$this, 'lutec_likes_callback']);

        add_action( 'admin_menu', [$this, 'lutec_likes_admin_page']);
    }

    function init()
    {
        global $wpdb;

        $wpdb->likes = $wpdb->prefix . 'posts_likes';

        $db_ver = get_option('lutec_db_ver', 0);
        if ($db_ver < LUTEC_VERSION) {
            $this->db_upgrade();
        }
    }

    function template($attr)
    {
        $params = shortcode_atts([
            'id' => '',
            'class' => 'post__likes',
        ], $attr);

        $likes = $this->get_post_likes($params['id']);

        ?>
        <div data-id="<?php esc_attr_e($params['id']); ?>" class="<?php esc_attr_e($params['class']); ?> likes">
            <button class="likes__btn likes__btn--plus" aria-label="Поставить лайк"></button>
            <p class="likes__count <?php esc_attr_e($likes >= 0 ? 'likes__count--good' : 'likes__count--bad' ); ?>" aria-label="Количество лайков">
                <?php esc_html_e($likes); ?>
            </p>
            <button class="likes__btn likes__btn--minus" aria-label="Поставить дизлайк"></button>
        </div>
        <?php
    }

    function lutec_likes_callback()
    {
        global $wpdb;

        check_ajax_referer('lutec', 'nonce');

        $id = $_POST['post_id'];
        $value = $_POST['value'];

        if ($value > 0) {
            $value = 1;
        } else {
            $value = -1;
        }

        if (empty($id) || empty($value)) {
            wp_die('Required projectors are empty', 400);
        }

        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $_SERVER['HTTP_REFERER'];

        $like_id = $wpdb->get_var("SELECT id FROM {$wpdb->likes} WHERE post_id = '{$id}' AND user_ip = INET6_ATON('{$ip}') ");

        if (empty($like_id)) {
            $query_values = esc_sql($id) . ", INET6_ATON('" . esc_sql($ip) . "'), '" . esc_sql($value) . "', '" . esc_sql($url) . "', '" . esc_sql(date("Y-m-d H:i:s")) . "'";
            $wpdb->query("INSERT {$wpdb->likes} (post_id, user_ip, like_value, url, timestamp) VALUES ({$query_values})");
        } else {
            $wpdb->update($wpdb->likes,
                [
                    'like_value' => $value,
                    'url' => $url,
                    'timestamp' => date("Y-m-d H:i:s")
                ],
                [
                    'id' => $like_id
                ],
                ['%d', '%s', '%s'],
                ['%d']
            );
        }

        echo $this->get_post_likes($id);

        wp_die();
    }

    function lutec_likes_admin_page(){
        $hook = add_menu_page(
            __('Лайки', 'lutes'),
            __('Лайки', 'lutes'),
            'manage_options',
            'posts-likes',
            [$this, 'likes_table_page'],
            '',
            6
        );

        add_action( "load-$hook", [$this, 'likes_table_page_load'] );
    }

    function likes_table_page_load(){
        require_once ('admin/Lutes-list-table.php');
        $GLOBALS['Lutes_List_Table'] = new Lutes_List_Table();
    }

    function likes_table_page(){
        ?>
        <div class="wrap">
            <h2><?php echo get_admin_page_title() ?></h2>

            <?php
            // выводим таблицу на экран где нужно
            echo '<form action="" method="POST">';
            $GLOBALS['Lutes_List_Table']->display();
            echo '</form>';
            ?>

        </div>
        <?php
    }

    protected function get_post_likes($post_id)
    {
        global $wpdb;

        $likes = $wpdb->get_var("SELECT SUM(like_value) AS likes FROM {$wpdb->likes} WHERE post_id = '{$post_id}'");

        if(empty($likes)){
            return 0;
        }

        return $likes;
    }

    protected function db_upgrade()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $query = "CREATE TABLE {$wpdb->likes} (
            id bigint(20) unsigned NOT NULL auto_increment,
            post_id bigint(20) unsigned NOT NULL,
            user_ip varbinary(16) NOT NULL,
            like_value tinyint(1) NOT NULL,
            url varchar(255) NOT NULL default '',
            timestamp DATETIME NOT NULL default '0000-00-00 00:00:00',
            PRIMARY KEY (id),
            KEY post_id (post_id),
            KEY user_ip (user_ip)
        ) {$charset_collate}";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($query);

        update_option('my_db_version', LUTEC_VERSION);
    }
}

new Lutec_Likes;