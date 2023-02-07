<?php

if ( ! defined( 'LUTEC_VERSION' ) ) {
    define( 'LUTEC_VERSION', '1' );
}

function lutec_scripts() {
    wp_enqueue_style( 'lutec-style', get_stylesheet_uri(), array(), LUTEC_VERSION );
    wp_enqueue_style('lutec-normalize', get_stylesheet_directory_uri() . '/assets/css/normalize.css', array(), LUTEC_VERSION );
    wp_enqueue_style('lutec-main', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), LUTEC_VERSION );
    wp_enqueue_style('lutec-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Roboto:wght@400;700&display=swap', array(), null);

    wp_enqueue_script('lutec-likes', get_stylesheet_directory_uri() . '/assets/js/likes.js', array(), LUTEC_VERSION, true);

    wp_localize_script( 'lutec-likes', 'lutec', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'  => wp_create_nonce('lutec'),
    ));
}
add_action( 'wp_enqueue_scripts', 'lutec_scripts' );

require_once ('inc/likes/Lutec-likes.php');