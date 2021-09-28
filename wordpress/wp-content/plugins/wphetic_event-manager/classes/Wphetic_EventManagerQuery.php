<?php


class Wphetic_EventManagerQuery
{
    private $nbre_posts;
    private $query;

    public function __construct(int $nbre_posts = 3)
    {
        $this->nbre_posts = $nbre_posts;
        $this->query();
    }

    public function query()
    {
        $args = [
            'post_type' => 'event',
            'posts_per_page' => $this->nbre_posts,
            'orderby' => 'rand'
        ];

        $this->query = new WP_Query($args);
    }

    public function render()
    {
        if ($this->query->have_posts()) :
            ob_start();
            ?>
            <div class="container marketing">
                <div class="row" style="justify-content: center">
                    <?php
                    while ($this->query->have_posts()) :
                        $this->query->the_post();
                        ?>
                        <div class="col-lg-4" style="margin-bottom: 1.5rem; text-align: center;">
                            <img class="bd-placeholder-img rounded-circle" width="140" height="140"
                                 style="object-fit: cover" alt="cover"
                                 src="<?= get_the_post_thumbnail_url(); ?>"/>
                            <h2 style="font-weight: 400;"><?php the_title(); ?></h2>
                            <p><?php the_excerpt(); ?></p>
                            <p><a class="btn btn-secondary"
                                  href="<?= get_post_permalink(); ?>">View details &raquo;</a>
                            </p>
                        </div><!-- /.col-lg-4 -->
                    <?php endwhile; ?>
                </div><!-- /.row -->
            </div>
        <?php endif;
        return ob_get_clean();
    }
}