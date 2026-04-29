<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Swap Requests via AJAX
 */
function tabadla_handle_swap_request() {
    check_ajax_referer( 'tabadla_swap_nonce', 'nonce' );

    $listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
    $user_id = get_current_user_id();

    if ( ! $user_id ) {
        wp_send_json_error( array( 'message' => __( 'Please login to request a swap.', 'tabadla' ) ) );
    }

    if ( ! $listing_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid listing.', 'tabadla' ) ) );
    }

    // Logic to store the swap request (e.g., in a custom table or meta)
    // For simplicity, we'll store it as a meta field on the listing or a custom comment type
    // Better yet, let's create a "Swap Request" meta entry
    $requests = get_post_meta( $listing_id, '_tabadla_swap_requests', true );
    if ( ! is_array( $requests ) ) {
        $requests = array();
    }

    if ( in_array( $user_id, $requests ) ) {
        wp_send_json_error( array( 'message' => __( 'You have already requested a swap for this item.', 'tabadla' ) ) );
    }

    $requests[] = $user_id;
    update_post_meta( $listing_id, '_tabadla_swap_requests', $requests );

    // Trigger Notifications
    tabadla_trigger_swap_notifications( $listing_id, $user_id );

    wp_send_json_success( array( 'message' => __( 'Swap request sent successfully!', 'tabadla' ) ) );
}
add_action( 'wp_ajax_tabadla_swap_request', 'tabadla_handle_swap_request' );
add_action( 'wp_ajax_nopriv_tabadla_swap_request', 'tabadla_handle_swap_request' );

/**
 * Trigger Notifications (WhatsApp/Email)
 */
function tabadla_trigger_swap_notifications( $listing_id, $requester_id ) {
    $owner_id = get_post_field( 'post_author', $listing_id );
    $owner_email = get_the_author_meta( 'user_email', $owner_id );
    $listing_title = get_the_title( $listing_id );
    $requester_name = get_the_author_meta( 'display_name', $requester_id );

    // 1. Email Notification
    $subject = sprintf( __( 'New Swap Request for %s', 'tabadla' ), $listing_title );
    $message = sprintf( __( '%s wants to swap an item with your "%s". Check your dashboard to respond!', 'tabadla' ), $requester_name, $listing_title );
    wp_mail( $owner_email, $subject, $message );

    // 2. WhatsApp Notification (Placeholder - requires API key)
    // tabadla_send_whatsapp_notification($owner_id, $message);
}

/**
 * Helper to generate WhatsApp Link
 */
function tabadla_get_whatsapp_link( $listing_id ) {
    $owner_id = get_post_field( 'post_author', $listing_id );
    $phone = get_user_meta( $owner_id, 'whatsapp_number', true ); // Custom user meta
    $listing_title = get_the_title( $listing_id );
    $message = urlencode( "Hi, I'm interested in swapping/buying your item: " . $listing_title );
    
    if ( empty( $phone ) ) {
        return '#';
    }

    return "https://wa.me/" . preg_replace( '/[^0-9]/', '', $phone ) . "?text=" . $message;
}
