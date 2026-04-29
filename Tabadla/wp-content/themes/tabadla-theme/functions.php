<?php
/**
 * Tabadla Theme Functions
 */

function tabadla_theme_setup() {
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'tabadla' ),
        'mobile'  => __( 'Mobile Menu', 'tabadla' ),
    ) );
}
add_action( 'after_setup_theme', 'tabadla_theme_setup' );

function tabadla_enqueue_assets() {
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;600;700;800&display=swap', array(), null );
    wp_enqueue_style( 'tabadla-style', get_stylesheet_uri(), array(), '1.0.0' );
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0' );
    wp_enqueue_style( 'aos-css', 'https://unpkg.com/aos@2.3.1/dist/aos.css', array(), '2.3.1' );

    wp_enqueue_script( 'aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '2.3.1', true );
    wp_enqueue_script( 'tabadla-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery', 'aos-js' ), '1.0.0', true );

    // Localize script for AJAX
    wp_localize_script( 'tabadla-scripts', 'tabadla_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'tabadla_swap_nonce' )
    ) );
}
add_action( 'wp_enqueue_scripts', 'tabadla_enqueue_assets' );

/**
 * Get Total Water Saved (Swap-O-Meter)
 */
function tabadla_get_total_water_saved() {
    global $wpdb;
    $total = $wpdb->get_var( "SELECT SUM(meta_value) FROM $wpdb->postmeta WHERE meta_key = '_tabadla_water_saved'" );
    return $total ? number_format( $total ) : '0';
}

/**
 * Helper to display Hygiene Badge
 */
function tabadla_display_hygiene_badge( $post_id ) {
    $status = get_post_meta( $post_id, '_tabadla_hygiene_status', true );
    if ( $status === 'verified' ) {
        echo '<span class="badge badge-verified"><i class="fas fa-check-circle"></i> ' . __( 'Verified Sanitized', 'tabadla' ) . '</span>';
    }
}
