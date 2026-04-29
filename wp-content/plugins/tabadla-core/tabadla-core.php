<?php
/**
 * Plugin Name: Tabadla Core
 * Description: Core functionality for Tabadla - Circular Fashion Exchange. Handles CPTs, Taxonomies, and Swap logic.
 * Version: 1.0.0
 * Author: Tabadla
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Register Custom Post Type: Listing
function tabadla_register_listing_cpt() {
	$labels = array(
		'name'               => _x( 'Listings', 'post type general name', 'tabadla' ),
		'singular_name'      => _x( 'Listing', 'post type singular name', 'tabadla' ),
		'menu_name'          => _x( 'Listings', 'admin menu', 'tabadla' ),
		'name_admin_bar'     => _x( 'Listing', 'add new on admin bar', 'tabadla' ),
		'add_new'            => _x( 'Add New', 'listing', 'tabadla' ),
		'add_new_item'       => __( 'Add New Listing', 'tabadla' ),
		'new_item'           => __( 'New Listing', 'tabadla' ),
		'edit_item'          => __( 'Edit Listing', 'tabadla' ),
		'view_item'          => __( 'View Listing', 'tabadla' ),
		'all_items'          => __( 'All Listings', 'tabadla' ),
		'search_items'       => __( 'Search Listings', 'tabadla' ),
		'parent_item_colon'  => __( 'Parent Listings:', 'tabadla' ),
		'not_found'          => __( 'No listings found.', 'tabadla' ),
		'not_found_in_trash' => __( 'No listings found in Trash.', 'tabadla' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'listings' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-store',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'author' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'listing', $args );
}
add_action( 'init', 'tabadla_register_listing_cpt' );

// 2. Register Taxonomies
function tabadla_register_taxonomies() {
	// Category
	register_taxonomy( 'listing_cat', 'listing', array(
		'label'        => __( 'Categories', 'tabadla' ),
		'rewrite'      => array( 'slug' => 'listing-category' ),
		'hierarchical' => true,
		'show_in_rest' => true,
	) );

	// Condition
	register_taxonomy( 'listing_condition', 'listing', array(
		'label'        => __( 'Condition', 'tabadla' ),
		'rewrite'      => array( 'slug' => 'listing-condition' ),
		'hierarchical' => false,
		'show_in_rest' => true,
	) );

	// Size
	register_taxonomy( 'listing_size', 'listing', array(
		'label'        => __( 'Size', 'tabadla' ),
		'rewrite'      => array( 'slug' => 'listing-size' ),
		'hierarchical' => false,
		'show_in_rest' => true,
	) );

    // Gender
	register_taxonomy( 'listing_gender', 'listing', array(
		'label'        => __( 'Gender', 'tabadla' ),
		'rewrite'      => array( 'slug' => 'listing-gender' ),
		'hierarchical' => false,
		'show_in_rest' => true,
	) );

	// Location
	register_taxonomy( 'listing_location', 'listing', array(
		'label'        => __( 'Location', 'tabadla' ),
		'rewrite'      => array( 'slug' => 'listing-location' ),
		'hierarchical' => true,
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'tabadla_register_taxonomies' );

// 3. Register Meta Fields
function tabadla_add_listing_meta_boxes() {
    add_meta_box(
        'listing_details',
        __( 'Listing Details', 'tabadla' ),
        'tabadla_listing_details_callback',
        'listing',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tabadla_add_listing_meta_boxes' );

function tabadla_listing_details_callback( $post ) {
    wp_nonce_field( 'tabadla_listing_meta_nonce', 'tabadla_listing_meta_nonce' );
    $price = get_post_meta( $post->ID, '_tabadla_price', true );
    $hygiene_status = get_post_meta( $post->ID, '_tabadla_hygiene_status', true );
    $water_saved = get_post_meta( $post->ID, '_tabadla_water_saved', true );
    ?>
    <p>
        <label for="tabadla_price"><?php _e( 'Price (for Buy Now)', 'tabadla' ); ?></label><br>
        <input type="number" id="tabadla_price" name="tabadla_price" value="<?php echo esc_attr( $price ); ?>" step="0.01">
    </p>
    <p>
        <label for="tabadla_hygiene_status"><?php _e( 'Hygiene Status', 'tabadla' ); ?></label><br>
        <select id="tabadla_hygiene_status" name="tabadla_hygiene_status">
            <option value="pending" <?php selected( $hygiene_status, 'pending' ); ?>><?php _e( 'Pending Verification', 'tabadla' ); ?></option>
            <option value="verified" <?php selected( $hygiene_status, 'verified' ); ?>><?php _e( 'Verified Sanitized', 'tabadla' ); ?></option>
            <option value="unverified" <?php selected( $hygiene_status, 'unverified' ); ?>><?php _e( 'Unverified', 'tabadla' ); ?></option>
        </select>
    </p>
    <p>
        <label for="tabadla_water_saved"><?php _e( 'Water Saved (Liters)', 'tabadla' ); ?></label><br>
        <input type="number" id="tabadla_water_saved" name="tabadla_water_saved" value="<?php echo esc_attr( $water_saved ); ?>">
    </p>
    <?php
}

function tabadla_save_listing_meta( $post_id ) {
    if ( ! isset( $_POST['tabadla_listing_meta_nonce'] ) || ! wp_verify_nonce( $_POST['tabadla_listing_meta_nonce'], 'tabadla_listing_meta_nonce' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['tabadla_price'] ) ) {
        update_post_meta( $post_id, '_tabadla_price', sanitize_text_field( $_POST['tabadla_price'] ) );
    }
    if ( isset( $_POST['tabadla_hygiene_status'] ) ) {
        update_post_meta( $post_id, '_tabadla_hygiene_status', sanitize_text_field( $_POST['tabadla_hygiene_status'] ) );
    }
    if ( isset( $_POST['tabadla_water_saved'] ) ) {
        update_post_meta( $post_id, '_tabadla_water_saved', sanitize_text_field( $_POST['tabadla_water_saved'] ) );
    }
}
add_action( 'save_post', 'tabadla_save_listing_meta' );

// 4. Image Compression Logic for unaux.com
function tabadla_compress_image_on_upload( $params ) {
    // Only compress if it's an image
    if ( strpos( $params['type'], 'image' ) === false ) {
        return $params;
    }

    $file = $params['file'];
    $image = wp_get_image_editor( $file );

    if ( ! is_wp_error( $image ) ) {
        // Set quality to 70 for aggressive compression
        $image->set_quality( 70 );
        $image->save( $file );
    }

    return $params;
}
add_filter( 'wp_handle_upload', 'tabadla_compress_image_on_upload' );

// 5. Advanced Search Logic
function tabadla_filter_listings_search( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_search() && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'listing' ) {
        $tax_query = array();

        if ( ! empty( $_GET['listing_cat'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'listing_cat',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $_GET['listing_cat'] ),
            );
        }

        if ( ! empty( $_GET['listing_location'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'listing_location',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $_GET['listing_location'] ),
            );
        }

        if ( count( $tax_query ) > 0 ) {
            $tax_query['relation'] = 'AND';
            $query->set( 'tax_query', $tax_query );
        }
    }
}
add_action( 'pre_get_posts', 'tabadla_filter_listings_search' );

// 6. User Profile Fields (WhatsApp)
function tabadla_user_profile_fields( $user ) { ?>
    <h3><?php _e('Tabadla Profile Information', 'tabadla'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="whatsapp_number"><?php _e('WhatsApp Number'); ?></label></th>
            <td>
                <input type="text" name="whatsapp_number" id="whatsapp_number" value="<?php echo esc_attr( get_the_author_meta( 'whatsapp_number', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description"><?php _e('Enter your WhatsApp number for swap requests (e.g., 923001234567).'); ?></span>
            </td>
        </tr>
    </table>
<?php }
add_action( 'show_user_profile', 'tabadla_user_profile_fields' );
add_action( 'edit_user_profile', 'tabadla_user_profile_fields' );

function tabadla_save_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) return false;
    update_user_meta( $user_id, 'whatsapp_number', $_POST['whatsapp_number'] );
}
add_action( 'personal_options_update', 'tabadla_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'tabadla_save_user_profile_fields' );

// Include Swap Logic
require_once plugin_dir_path( __FILE__ ) . 'includes/swap-logic.php';
