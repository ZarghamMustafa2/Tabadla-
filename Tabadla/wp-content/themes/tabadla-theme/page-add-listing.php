<?php
/**
 * Template Name: Add Listing
 */

if ( ! is_user_logged_in() ) {
    auth_redirect();
}

$message = '';
if ( isset( $_POST['tabadla_submit_listing'] ) ) {
    // Basic verification
    if ( ! isset( $_POST['tabadla_listing_nonce'] ) || ! wp_verify_nonce( $_POST['tabadla_listing_nonce'], 'tabadla_listing_action' ) ) {
        $message = 'Security check failed.';
    } else {
        $title = sanitize_text_field( $_POST['listing_title'] );
        $content = wp_kses_post( $_POST['listing_description'] );
        $price = sanitize_text_field( $_POST['listing_price'] );
        $cat = intval( $_POST['listing_cat'] );
        $location = intval( $_POST['listing_location'] );

        $new_post = array(
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'pending', // Admin approval needed
            'post_type'    => 'listing',
            'post_author'  => get_current_user_id(),
        );

        $post_id = wp_insert_post( $new_post );

        if ( $post_id ) {
            update_post_meta( $post_id, '_tabadla_price', $price );
            wp_set_post_terms( $post_id, array( $cat ), 'listing_cat' );
            wp_set_post_terms( $post_id, array( $location ), 'listing_location' );

            // Handle Image Upload
            if ( ! empty( $_FILES['listing_image']['name'] ) ) {
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );

                $attachment_id = media_handle_upload( 'listing_image', $post_id );
                if ( ! is_wp_error( $attachment_id ) ) {
                    set_post_thumbnail( $post_id, $attachment_id );
                }
            }

            $message = 'Listing submitted for review! We will notify you once it is live.';
        }
    }
}

get_header(); ?>

<main class="container" style="margin-top: 3rem; max-width: 600px;">
    <div style="background: white; padding: 2.5rem; border-radius: 20px; box-shadow: var(--shadow);">
        <h1 style="margin-bottom: 0.5rem; text-align: center;">List Your Item</h1>
        <p style="text-align: center; color: #666; margin-bottom: 2rem;">Join the circular fashion movement.</p>

        <?php if ( $message ) : ?>
            <div class="alert" style="background: #e6fcf5; color: #0ca678; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #c3fae8;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <?php wp_nonce_field( 'tabadla_listing_action', 'tabadla_listing_nonce' ); ?>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Item Title</label>
                <input type="text" name="listing_title" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 10px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Description</label>
                <textarea name="listing_description" rows="4" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 10px;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Category</label>
                    <?php wp_dropdown_categories( array(
                        'taxonomy'     => 'listing_cat',
                        'name'         => 'listing_cat',
                        'show_option_none' => 'Select Category',
                        'class'        => 'form-control',
                        'hide_empty'   => 0,
                        'style'        => 'width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 10px;'
                    ) ); ?>
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Location</label>
                    <?php wp_dropdown_categories( array(
                        'taxonomy'     => 'listing_location',
                        'name'         => 'listing_location',
                        'show_option_none' => 'Select Location',
                        'class'        => 'form-control',
                        'hide_empty'   => 0,
                        'style'        => 'width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 10px;'
                    ) ); ?>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Price (Optional - Leave 0 for Swap Only)</label>
                <input type="number" name="listing_price" value="0" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 10px;">
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Item Photo</label>
                <input type="file" name="listing_image" accept="image/*" required style="width: 100%;">
            </div>

            <button type="submit" name="tabadla_submit_listing" class="btn btn-primary" style="width: 100%; padding: 1rem;">Submit Listing</button>
        </form>
    </div>
</main>

<?php get_footer(); ?>
