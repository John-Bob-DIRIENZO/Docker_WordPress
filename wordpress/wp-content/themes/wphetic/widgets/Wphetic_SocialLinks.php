<?php


class Wphetic_SocialLinks extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('wphetic_social_links', 'Réseaux Sociaux');
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if ($instance['title']) {
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        }

        echo '<div class="d-flex justify-content-start">';
        if ($instance['facebook']) {
            ?>
            <a href="<?= $instance['facebook']; ?>" style="text-decoration: none" class="m-2">
                <span class="dashicons dashicons-facebook" style="font-size: 2em"></span>
            </a>
            <?php
        }
        if ($instance['instagram']) {
            ?>
            <a href="<?= $instance['instagram']; ?>" style="text-decoration: none" class="m-2">
                <span class="dashicons dashicons-instagram" style="font-size: 2em"></span>
            </a>
            <?php
        }
        if ($instance['twitter']) {
            ?>
            <a href="<?= $instance['twitter']; ?>" style="text-decoration: none" class="m-2">
                <span class="dashicons dashicons-twitter" style="font-size: 2em"></span>
            </a>
            <?php
        }
        echo '</div>';
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
                   value="<?= esc_attr($instance['title']); ?>"
                   class="widefat">
        </p>
        <p>
            <label for="<?= $this->get_field_id('facebook'); ?>">Facebook</label>
            <input type="text"
                   name="<?= $this->get_field_name('facebook'); ?>"
                   id="<?= $this->get_field_id('facebook'); ?>"
                   value="<?= esc_attr($instance['facebook']); ?>"
                   class="widefat">
        </p>
        <p>
            <label for="<?= $this->get_field_id('instagram'); ?>">Instagram</label>
            <input type="text"
                   name="<?= $this->get_field_name('instagram'); ?>"
                   id="<?= $this->get_field_id('instagram'); ?>"
                   value="<?= esc_attr($instance['instagram']); ?>"
                   class="widefat">
        </p>
        <p>
            <label for="<?= $this->get_field_id('twitter'); ?>">Twitter</label>
            <input type="text"
                   name="<?= $this->get_field_name('twitter'); ?>"
                   id="<?= $this->get_field_id('twitter'); ?>"
                   value="<?= esc_attr($instance['twitter']); ?>"
                   class="widefat">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
}