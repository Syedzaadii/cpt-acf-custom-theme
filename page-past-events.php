<?php get_header();
page_banner([
    'title' => 'Past Events',
    'subtitle' => 'Here is the list of Past events.'
]);
?>

<div class="container container--narrow page-section">
    <?php
    $today       = date('Ymd');
    $event_query = new WP_Query([
        'paged'          => get_query_var('paged', 1),
        'post_type'      => 'event',
        'posts_per_page' => 2,
        'order'          => 'DESC',
        'order_by'       => 'meta_value_num',
        'meta_key'       => 'event_date',
        'meta_query'     => [
            [
                'key'     => 'event_date',
                'compare' => '<',
                'value'   => $today,
                'type'  => 'numeric'
            ]
        ]
    ]);
    while ($event_query->have_posts()) : $event_query->the_post();
        get_template_part('template-parts/content', 'event');
    endwhile;;
    echo paginate_links([
        'total'  => $event_query->max_num_pages
    ]);
    ?>

</div>

<?php get_footer(); ?>