<?php get_header(); ?>

<main class="container" style="margin-top: 2rem; margin-bottom: 5rem;">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
        $price = get_post_meta( get_the_ID(), '_tabadla_price', true );
        $water = get_post_meta( get_the_ID(), '_tabadla_water_saved', true );
        $whatsapp_link = tabadla_get_whatsapp_link( get_the_ID() );
    ?>
        <div class="product-layout" style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            <div class="product-gallery">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="main-image" style="border-radius: 20px; overflow: hidden; box-shadow: var(--shadow);">
                        <?php the_post_thumbnail( 'large', array( 'style' => 'width: 100%; height: auto;' ) ); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="product-info">
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <?php tabadla_display_hygiene_badge( get_the_ID() ); ?>
                    <?php if ( $water ) : ?>
                        <span class="badge" style="background: #e3f2fd; color: #1976d2;"><i class="fas fa-tint"></i> <?php echo $water; ?>L Water Saved</span>
                    <?php endif; ?>
                </div>

                <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;"><?php the_title(); ?></h1>
                
                <div class="price-tag" style="font-size: 2rem; color: var(--secondary-coral); font-weight: 700; margin-bottom: 1.5rem;">
                    <?php if ( $price ) : ?>
                        RS. <?php echo esc_html( $price ); ?>
                    <?php else : ?>
                        Available for Swap
                    <?php endif; ?>
                </div>

                <div class="description" style="margin-bottom: 2rem; font-size: 1.1rem; color: #555;">
                    <?php the_content(); ?>
                </div>

                <div class="product-attributes" style="margin-bottom: 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="attr">
                        <strong>Category:</strong> <?php echo get_the_term_list( get_the_ID(), 'listing_cat', '', ', ' ); ?>
                    </div>
                    <div class="attr">
                        <strong>Condition:</strong> <?php echo get_the_term_list( get_the_ID(), 'listing_condition', '', ', ' ); ?>
                    </div>
                    <div class="attr">
                        <strong>Size:</strong> <?php echo get_the_term_list( get_the_ID(), 'listing_size', '', ', ' ); ?>
                    </div>
                    <div class="attr">
                        <strong>Location:</strong> <?php echo get_the_term_list( get_the_ID(), 'listing_location', '', ', ' ); ?>
                    </div>
                </div>

                <div class="action-buttons" style="display: flex; flex-direction: column; gap: 1rem;">
                    <button class="btn btn-primary btn-swap" data-id="<?php the_ID(); ?>" style="padding: 1.2rem; font-size: 1.1rem;">Request Swap</button>
                    
                    <?php if ( $price ) : ?>
                        <button class="btn btn-secondary" style="padding: 1.2rem; font-size: 1.1rem;">Buy Now (Rs. <?php echo $price; ?>)</button>
                    <?php endif; ?>

                    <a href="<?php echo esc_url( $whatsapp_link ); ?>" class="btn btn-outline" style="padding: 1.2rem; font-size: 1.1rem;">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    <?php endwhile; endif; ?>
</main>

<style>
@media (max-width: 768px) {
    .product-layout {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }
    .product-info h1 {
        font-size: 1.8rem !important;
    }
}
</style>

<?php get_footer(); ?>
