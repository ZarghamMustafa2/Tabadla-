<?php get_header(); ?>

<main class="container" style="margin-top: 2rem;">
    <div class="search-filters" style="margin-bottom: 3rem; background: white; padding: 1.5rem; border-radius: 15px; box-shadow: var(--shadow);">
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="hidden" name="post_type" value="listing" />
            <input type="text" name="s" placeholder="Search clothes..." style="flex: 2; min-width: 200px; padding: 0.8rem; border: 1px solid #ddd; border-radius: 10px;">
            
            <select name="listing_cat" style="flex: 1; min-width: 150px; padding: 0.8rem; border: 1px solid #ddd; border-radius: 10px;">
                <option value="">All Categories</option>
                <?php 
                $cats = get_terms( array( 'taxonomy' => 'listing_cat', 'hide_empty' => false ) );
                foreach ( $cats as $cat ) echo '<option value="'.$cat->slug.'">'.$cat->name.'</option>';
                ?>
            </select>

            <select name="listing_location" style="flex: 1; min-width: 150px; padding: 0.8rem; border: 1px solid #ddd; border-radius: 10px;">
                <option value="">All Multan Areas</option>
                <?php 
                $locs = get_terms( array( 'taxonomy' => 'listing_location', 'hide_empty' => false ) );
                foreach ( $locs as $loc ) echo '<option value="'.$loc->slug.'">'.$loc->name.'</option>';
                ?>
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;"><i class="fas fa-filter"></i> Filter</button>
        </form>
    </div>

    <div class="listing-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
            $price = get_post_meta( get_the_ID(), '_tabadla_price', true );
        ?>
            <div class="listing-card">
                <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'medium', array( 'class' => 'listing-image' ) ); ?>
                    </a>
                <?php endif; ?>
                
                <div class="listing-content">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <h3 class="listing-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php tabadla_display_hygiene_badge( get_the_ID() ); ?>
                    </div>
                    
                    <div class="listing-price">
                        <?php if ( $price ) : ?>
                            RS. <?php echo esc_html( $price ); ?>
                        <?php else : ?>
                            Swap Only
                        <?php endif; ?>
                    </div>

                    <div class="listing-meta">
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo strip_tags( get_the_term_list( get_the_ID(), 'listing_location', '', ', ' ) ); ?></span>
                        <span><i class="fas fa-tag"></i> <?php echo strip_tags( get_the_term_list( get_the_ID(), 'listing_cat', '', ', ' ) ); ?></span>
                    </div>

                    <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                        <button class="btn btn-primary btn-swap" data-id="<?php the_ID(); ?>" style="flex: 1; font-size: 0.9rem;">Request Swap</button>
                        <?php if ( $price ) : ?>
                            <a href="<?php the_permalink(); ?>" class="btn btn-secondary" style="flex: 1; font-size: 0.9rem;">Buy Now</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; else : ?>
            <p><?php _e( 'No listings found.' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
