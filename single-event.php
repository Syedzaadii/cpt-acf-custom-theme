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
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i
                    class="fa fa-home" aria-hidden="true"></i> Back to Events</a> <span
                class="metabox__main"><?php the_title(); ?></span>
        </p>
    </div>
    <div class="generic-content">
        <?php the_content(); ?>
    </div>

    <?php
        $related_programs = get_field('related_programs');
        if ($related_programs) :
        ?>
    <hr class="section-break">
    <h2 class="headline headline--medium"> Related Programs </h2>

    <ul class="link-list min-list">
        <?php
                foreach ($related_programs as $related_program) :
                ?>
        <li><a
                href="<?php echo get_the_permalink($related_program); ?>"><?php echo get_the_title($related_program);  ?></a>
        </li>

        <?php endforeach;
            endif;
            ?>
    </ul>
</div>

<?php
endwhile;
get_footer(); ?>