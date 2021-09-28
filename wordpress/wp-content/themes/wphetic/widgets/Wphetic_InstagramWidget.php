<?php


class Wphetic_InstagramWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('wphetic_instagram_widget', 'Feed Instagram');
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo $args['before_title'] . $instance['title'] . $args['after_title'];
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <div id="instagram-feed1" class="w-75 mt-3 mb-3"></div>
        <script src="<?= get_template_directory_uri() . '/assets/js/jquery-feed-instagram-graph.js'; ?>"
                token="<?= $instance['token']; ?>"
                nbre_posts="<?= $instance['nbre_posts']; ?>"></script>
        <?php

        echo $args['after_widget'];

    }

    public function form($instance)
    {
        ?>
        <p>
            <label for="<?= $this->get_field_id('title'); ?>">Titre</label>
            <input type="text"
                   name="<?= $this->get_field_name('title'); ?>"
                   id="<?= $this->get_field_id('title'); ?>"
                   value="<?= $instance['title'] ?? ''; ?>"
                   class="widefat">
        </p>
        <p>
            <label for="<?= $this->get_field_id('token'); ?>">Token Instagram</label>
            <input type="text"
                   name="<?= $this->get_field_name('token'); ?>"
                   id="<?= $this->get_field_id('token'); ?>"
                   value="<?= $instance['token'] ?? ''; ?>"
                   class="widefat">
        </p>
        <p>
            <label for="<?= $this->get_field_id('nbre_posts'); ?>">Nombre de posts</label>
            <input type="number"
                   name="<?= $this->get_field_name('nbre_posts'); ?>"
                   id="<?= $this->get_field_id('nbre_posts'); ?>"
                   value="<?= $instance['nbre_posts'] ?? ''; ?>"
                   class="tiny-text">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        // Si je ne fais aucun contrôle
        return $new_instance;
    }
}