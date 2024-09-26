<?php get_header();
page_banner([
    'title' => 'Search',
    'subtitle' => 'Learn how the school of your dreams got started.'
]);
?>

<?php $parent_id = wp_get_post_parent_id(get_the_ID()); ?>

<div class="container container--narrow page-section">
    <div class="generic-content">
        <?php get_search_form(); ?>
    </div>
</div>

<?php get_footer(); ?>