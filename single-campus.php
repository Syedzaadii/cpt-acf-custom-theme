<?php get_header();
while (have_posts()) : the_post();
    page_banner([
        'title' => get_the_title(),
        'subtitle' => 'Learn how the school of your dreams got started.'
    ]);
?>

<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo esc_url(site_url('/blog')); ?>"><i class="fa fa-home"
                    aria-hidden="true"></i> Back to Campus</a> <span class="metabox__main">
                <?php the_title(); ?></span>
        </p>
    </div>
    <div class="generic-content">
        <?php the_content(); ?>
    </div>
</div>

<?php
endwhile;
get_footer();
?>