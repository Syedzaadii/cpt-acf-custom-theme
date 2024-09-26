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
            <a class="metabox__blog-home-link" href="<?php echo esc_url(get_post_type_archive_link('program')); ?>"><i
                    class="fa fa-home" aria-hidden="true"></i> Back to Programs</a> <span
                class="metabox__main"><?php the_title(); ?></span>
        </p>
    </div>
    <div class="generic-content">
        <?php the_content(); ?>
    </div>


    <?php
        $professor_query = new WP_Query([
            'post_type'      => 'professor',
            'posts_per_page' => -1,
            'order'          => 'ASC',
            'meta_query'    => [
                [
                    'key'     => 'related_programs',
                    'compare' => 'LIKE',
                    'value'   => '"' . get_the_ID() . '"'
                ]
            ]
        ]);
        if ($professor_query->have_posts()) : ?>
    <hr class="section-break">
    <h2 class="headline headline--medium">Professor </h2>
    <ul class="professor-cards">
        <?php while ($professor_query->have_posts()) : $professor_query->the_post(); ?>
        <li class="professor-card__list-item">
            <a href="<?php the_permalink(); ?>" class="professor-card">

                <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="professor-card__image">
                <span class="professor-card__name"><?php the_title(); ?></span>
            </a>
        </li>

        <?php endwhile;
            endif;
            wp_reset_postdata();
            ?>
    </ul>
    <?php
            $today = date('Ymd');
            $event_query = new WP_Query([
                'post_type'      => 'event',
                'posts_per_page' => 2,
                'order'          => 'ASC',
                'order_by'       => 'meta_value_num',
                'meta_key'       => 'event_date',
                'meta_query'    => [
                    [
                        'key'     => 'event_date',
                        'compare' => '>=',
                        'value'   => $today,
                        'type'  => 'numeric'
                    ],
                    [
                        'key'     => 'related_programs',
                        'compare' => 'LIKE',
                        'value'   => '"' . get_the_ID() . '"'
                    ]
                ]
            ]);
            if ($event_query->have_posts()) : ?>
    <hr class="section-break">
    <h2 class="headline headline--medium">Upcoming Events</h2>
    <?php
                while ($event_query->have_posts()) : $event_query->the_post();
                    get_template_part('template-parts/content', 'event');
                endwhile;
            endif;
            wp_reset_postdata();
            ?>


</div>

<?php
endwhile;
get_footer(); ?>