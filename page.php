<?php get_header();
page_banner([
    'title' => get_the_title(),
    'subtitle' => 'Learn how the school of your dreams got started.'
]);
?>

<?php $parent_id = wp_get_post_parent_id(get_the_ID()); ?>

<div class="container container--narrow page-section">
    <?php
    if ($parent_id) :
    ?>
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo get_permalink($parent_id); ?>"><i class="fa fa-home"
                    aria-hidden="true"></i> Back to <?php echo get_the_title($parent_id); ?></a> <span
                class="metabox__main"><?php the_title(); ?></span>
        </p>
    </div>
    <?php endif; ?>

    <?php
    $has_child = get_pages([
        'child_of' => get_the_ID()
    ]);
    if ($has_child) :
    ?>
    <div class="page-links">
        <h2 class="page-links__title"><a href="#">About Us</a></h2>
        <ul class="min-list">

            <?php
                if ($parent_id) {
                    $child = $parent_id;
                } else {
                    $child = get_the_ID();
                }
                wp_list_pages([
                    'title_li'    => NULL,
                    'child_of'    => $child,
                    'sort_column' => 'menu_order'
                ]);
                ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="generic-content">
        <?php the_content(); ?>
    </div>
</div>

<?php get_footer(); ?>