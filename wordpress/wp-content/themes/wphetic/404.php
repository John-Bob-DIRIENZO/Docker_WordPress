<?php get_header(); ?>

<h1>Got lost ?</h1>
<img src="/wp-content/uploads/2021/09/confused-meme.gif">

<hr />

<?= do_shortcode('[display_events nbre_posts=2]'); ?>

<?php
echo '<hr /><pre>';
$role = get_role('administrator');
print_r($role->capabilities);
?>

</pre>
<hr />
<?= do_shortcode('[wphetic_form]'); ?>
<?php get_footer(); ?>
