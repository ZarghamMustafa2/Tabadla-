<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="container">
        <div class="logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                Tabadla<span>.</span>
            </a>
        </div>
        
        <div class="swap-o-meter">
            <i class="fas fa-tint" style="color: #00bcd4;"></i>
            <strong><?php echo tabadla_get_total_water_saved(); ?></strong> Liters Saved
        </div>

        <nav class="main-navigation">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'nav-menu',
                'fallback_cb'    => false,
            ) );
            ?>
        </nav>
    </div>
</header>
