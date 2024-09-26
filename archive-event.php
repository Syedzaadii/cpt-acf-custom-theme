<?php get_header();
page_banner([
    'title' => ' Upcomming Events',
    'subtitle' => 'Here is the list of Upcoming events.'
]);
?>



<div class="container container--narrow page-section">
    <?php
    while (have_posts()) : the_post();
        get_template_part('template-parts/content', 'event');
    endwhile;
    echo paginate_links();
    ?>
    <hr class="section-break">
    <p>Looking to recap of your events? <a href="<?php echo site_url('/past-events'); ?>">Check out past
            events</a></p>

</div>

<?php get_footer(); ?>