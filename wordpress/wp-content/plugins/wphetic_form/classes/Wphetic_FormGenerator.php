<?php


class Wphetic_FormGenerator
{
    public static function createForm()
    {
        ob_start();
        ?>

        <form action="<?= admin_url('admin-post.php'); ?>" method="post">
            <label for="message">Votre Message</label><br/>
            <textarea name="message" id="message"></textarea><br/>

            <!-- Le fameux champs d'action -->
            <input type="hidden" name="action" value="wphetic_form"/>

            <!-- Crée les champs de nonce et de referer -->
            <?php wp_nonce_field('random_action', 'random_nonce'); ?>

            <input type="submit" value="Envoyer"/>

        </form>

        <?php
        return ob_get_clean();
    }

    public static function handleForm()
    {
        if (!wp_verify_nonce($_POST['random_nonce'], 'random_action')) {
            die('Nonce invalide');
        }
        $message = $_POST['message'];
        wp_redirect($_POST['_wp_http_referer'] . "?message=" . $message);
        exit();
    }
}