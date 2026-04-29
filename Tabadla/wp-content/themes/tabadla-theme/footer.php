<footer class="site-footer">
    <div class="container">
        <p>&copy; <?php echo date( 'Y' ); ?> Tabadla. Circular Fashion Exchange.</p>
    </div>
</footer>

<nav class="mobile-nav">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fas fa-home"></i></a>
    <a href="<?php echo esc_url( get_post_type_archive_link( 'listing' ) ); ?>"><i class="fas fa-search"></i></a>
    <a href="<?php echo esc_url( home_url( '/add-listing' ) ); ?>" class="btn-primary" style="padding: 0.5rem; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%;"><i class="fas fa-plus"></i></a>
    <a href="#"><i class="fas fa-heart"></i></a>
    <a href="#"><i class="fas fa-user"></i></a>
</nav>

<a href="#" class="whatsapp-float">
    <i class="fab fa-whatsapp"></i>
</a>

<?php wp_footer(); ?>
</body>
</html>
