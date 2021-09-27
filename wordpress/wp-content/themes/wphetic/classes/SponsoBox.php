<?php


class SponsoBox
{
    private $metakey;
    private $price;

    public function __construct($metakey)
    {
        $this->metakey = $metakey;
        $this->price = $metakey . '_price';
        $this->register();
    }

    public function register()
    {
        add_action('add_meta_boxes', [$this, 'wphetic_add_metabox']);
        add_action('save_post', [$this, 'wphetic_save_metabox']);
    }

    public function wphetic_add_metabox()
    {
        add_meta_box(
            'sponso', // Le slug de notre box
            'Contenu sponsorisé', // Le titre de notre box
            [$this, 'wphetic_metabox_render'], // Le callback qui fera l'affichage
            'post', // Sur quels panneaux l'afficher
            'side' // A quel endroit du panneau
        );
    }

    public function wphetic_metabox_render($post)
    {
        $checked = get_post_meta($post->ID, $this->metakey, true) ? 'checked' : null;
        $price = get_post_meta($post->ID, $this->price, true) ? : null;
        ?>
        <input type="checkbox" value="true" name="<?= $this->metakey; ?>" id="sponso" <?= $checked; ?>/>
        <label for="sponso">Contenu sponsorisé</label> <br/>
        <label for="price">Prix de la sponso : </label>
        <input type="text" name="<?= $this->price; ?>" id="price" value="<?= $price; ?>">
        <?php
    }

    public function wphetic_save_metabox($post_id)
    {
        if ($_POST[$this->metakey] === 'true') {
            update_post_meta($post_id, $this->metakey, 'true');
        } else {
            delete_post_meta($post_id, $this->metakey);
        }

        if (isset($_POST[$this->price])) {
            update_post_meta($post_id, $this->price, $_POST[$this->price]);
        } else {
            delete_post_meta($post_id, $this->price);
        }
    }

    /**
     * @return mixed
     */
    public function getMetakey()
    {
        return $this->metakey;
    }
}