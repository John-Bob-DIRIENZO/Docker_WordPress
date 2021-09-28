<?php get_header(); ?>

<h1>Got lost ?</h1>
<img src="/wp-content/uploads/2021/09/confused-meme.gif">

<?php
echo '<hr><pre>';
$role = get_role('administrator');
print_r($role->capabilities);
?>
<?php get_footer(); ?>
