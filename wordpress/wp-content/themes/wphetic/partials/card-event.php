<div class="col">
    <div class="card">
        <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="...">
        <div class="card-body">

            <?php if (get_post_meta(get_the_ID(), 'wphetic_sponso', true)) : ?>
                <p class="alert alert-info">Contenu sponsorisé</p>
            <?php endif; ?>

            <h5 class="card-title"><?php the_title(); ?></h5>
            <p><small>Style(s) : <?php the_terms(get_the_ID(), 'style'); ?></small></p>
            <p class="card-text"><?php the_excerpt(); ?></p>
            <a href="<?php the_permalink(); ?>" class="btn btn-primary">Lire plus</a>
        </div>
    </div>
</div>