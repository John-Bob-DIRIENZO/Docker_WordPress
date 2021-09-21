</div>

<?php if (!is_front_page()) : ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">

                <?php wp_nav_menu([
                    'theme_location' => 'footer',
                    'menu_class' => 'navbar-nav',
                    'container' => false
                ]); ?>

            </div>
        </div>
    </nav>

<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>