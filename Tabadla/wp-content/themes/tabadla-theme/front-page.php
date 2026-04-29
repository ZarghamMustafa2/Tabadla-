<?php get_header(); ?>

<section class="hero">
    <div class="hero-content" data-aos="zoom-out" data-aos-duration="1500">
        <h1>Renew Your Style,<br>Respect the Planet<span>.</span></h1>
        <p>Tabadla is Multan's first premium circular fashion hub. Swap unstitched lawn, luxury wear, and vintage gems effortlessly.</p>
        
        <div class="search-bar-glass">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: flex; width: 100%;">
                <input type="hidden" name="post_type" value="listing" />
                <input type="text" name="s" placeholder="Search by brand, size, or location..." style="flex: 1; background: transparent; border: none; color: white; padding: 0.8rem 1.5rem; outline: none;">
                <button type="submit" style="background: var(--secondary-coral); color: white; border: none; padding: 0.8rem 2rem; border-radius: 50px; font-weight: 700; cursor: pointer;">Explore Now</button>
            </form>
        </div>
    </div>
</section>

<div class="container">
    <!-- Why Tabadla? -->
    <div style="text-align: center; margin: 4rem 0;">
        <h2 style="font-size: 2.5rem;" data-aos="fade-up">Why Tabadla?</h2>
        <p style="color: #666;" data-aos="fade-up" data-aos-delay="100">The first ethical fashion platform in Multan</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin-bottom: 6rem;">
        <div data-aos="fade-right">
            <h3 style="color: var(--primary-teal); margin-bottom: 1rem;"><i class="fas fa-leaf"></i> Eco-Conscious</h3>
            <p>Every piece of clothing takes 2,700L of water to make. Swapping extends its life and saves the planet.</p>
        </div>
        <div data-aos="fade-up">
            <h3 style="color: var(--primary-teal); margin-bottom: 1rem;"><i class="fas fa-shield-alt"></i> Hygiene First</h3>
            <p>Our "Hygiene Passport" ensures every item is sanitized and verified before it reaches your doorstep.</p>
        </div>
        <div data-aos="fade-left">
            <h3 style="color: var(--primary-teal); margin-bottom: 1rem;"><i class="fas fa-users"></i> Local Community</h3>
            <p>Connect with fashion lovers in Multan. Meet in University Area, Cantt, or Gulgasht for safe swaps.</p>
        </div>
    </div>

    <!-- Impact Tracker -->
    <?php 
    $total_water = tabadla_get_total_water_saved(); 
    ?>
    <div class="impact-section" data-aos="fade-right">
        <div>
            <h2 style="font-size: 2.2rem; margin-bottom: 1rem;">Our Shared Impact</h2>
            <p style="color: #666; margin-bottom: 2rem;">Every swap on Tabadla saves precious resources. Join thousands of Multanis making a difference.</p>
            <div class="impact-stats">
                <div class="stat-box">
                    <h3><?php echo $total_water; ?>L</h3>
                    <p>Water Saved</p>
                </div>
                <div class="stat-box">
                    <h3>450+</h3>
                    <p>Items Swapped</p>
                </div>
            </div>
        </div>
        <div style="background: var(--bg-off-white); padding: 2rem; border-radius: 30px; position: relative;" data-aos="fade-left">
            <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80" style="width: 100%; border-radius: 20px; box-shadow: var(--shadow);">
            <div style="position: absolute; bottom: -20px; right: -20px; background: var(--secondary-coral); color: white; padding: 1.5rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(255,127,80,0.4);">
                <i class="fas fa-leaf fa-2x"></i>
                <div style="font-weight: 800; font-size: 1.2rem;">Ecofriendly</div>
            </div>
        </div>
    </div>

    <!-- Recent Listings -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 2.2rem;" data-aos="fade-right">Fresh Picks</h2>
        <a href="<?php echo get_post_type_archive_link('listing'); ?>" style="font-weight: 700; color: var(--secondary-coral);">View All <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="listing-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
        <?php
        $args = array('post_type' => 'listing', 'posts_per_page' => 4);
        $recent = new WP_Query($args);
        if ($recent->have_posts()) : while ($recent->have_posts()) : $recent->the_post();
            $price = get_post_meta(get_the_ID(), '_tabadla_price', true);
        ?>
            <div class="listing-card" data-aos="fade-up">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium', array('class' => 'listing-image')); ?>
                    </a>
                <?php endif; ?>
                <div class="listing-content">
                    <h3 class="listing-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="listing-price"><?php echo $price ? 'RS. '.$price : 'Swap Only'; ?></div>
                    <a href="<?php the_permalink(); ?>" class="btn btn-primary" style="margin-top:1rem;">View Details</a>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); endif; ?>
    </div>
</div>

<?php get_footer(); ?>
