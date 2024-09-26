<?php get_header();
while (have_posts()) : the_post();
    page_banner();
?>

<div class="container container--narrow page-section">

    <div class="generic-content">
        <div class="row group">
            <div class="one-third">
                <?php the_post_thumbnail(); ?>
            </div>
            <div class="two-thirds">

                <?php
                    $likeCount = new WP_Query([
                        'post_type' => 'like',
                        'meta_query' => [
                            [
                                'key'     => 'liked_professor_id',
                                'compare' => '=',
                                'value'   => get_the_ID()
                            ]
                        ]
                    ]);
                    $existStatus = "no";
                    if (is_user_logged_in()) {

                        $alreadyLiked = new WP_Query([
                            'post_type'  => 'like',
                            'author'     => get_current_user_id(),
                            'meta_query' => [
                                [
                                    'key'     => 'liked_professor_id',
                                    'compare' => '=',
                                    'value'   => get_the_ID()
                                ]
                            ]
                        ]);

                        if ($alreadyLiked->found_posts) {
                            $existStatus = "yes";
                        }
                    }
                    ?>

                <span class="like-box" data-exists="<?php echo $existStatus; ?>" data-professor="<?php the_ID(); ?>"
                    data-like="<?php if (isset($alreadyLiked->posts[0]->ID)) {
                                                                                                                                        echo $alreadyLiked->posts[0]->ID;
                                                                                                                                    } ?>">
                    <i class="fa fa-heart-o"></i>
                    <i class="fa fa-heart"></i>
                    <span class="like-count"><?php echo $likeCount->found_posts ?></span>
                </span>

                <?php the_content(); ?>
            </div>
        </div>
    </div>

    <?php
        $related_programs = get_field('related_programs');
        if ($related_programs) :
        ?>
    <hr class="section-break">
    <h2 class="headline headline--medium"> Subject Taught </h2>

    <ul class="link-list min-list">
        <?php
                foreach ($related_programs as $related_program) :
                ?>
        <li><a
                href="<?php echo get_the_permalink($related_program); ?>"><?php echo get_the_title($related_program);  ?></a>
        </li>

        <?php
                endforeach;
            endif;
            ?>
    </ul>
</div>

<?php
endwhile;
get_footer(); ?>