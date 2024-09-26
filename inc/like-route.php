<?php

function fictional_custom_like_route()
{
    register_rest_route('fictional/v1/', 'manageLike', [
        'methods'   => 'POST',
        'callback'  =>  'createLike'
    ]);
    register_rest_route('fictional/v1/', 'manageLike', [
        'methods'   => 'DELETE',
        'callback'  =>  'removeLike'
    ]);
}
add_action('rest_api_init', 'fictional_custom_like_route');

function createLike($data)
{
    if (is_user_logged_in()) {

        $professorId = sanitize_text_field($data['professorId']);

        $alreadyLiked = new WP_Query([
            'post_type'  => 'like',
            'author'     => get_current_user_id(),
            'meta_query' => [
                [
                    'key'     => 'liked_professor_id',
                    'compare' => '=',
                    'value'   => $professorId
                ]
            ]
        ]);

        if ($alreadyLiked->found_posts == 0 and get_post_type($professorId) == 'professor') {

            return wp_insert_post([
                'post_type'   => 'like',
                'post_status' => 'publish',
                'post_title'  => 'Liked',
                'meta_input'    => [
                    'liked_professor_id' => $professorId
                ]
            ]);
        } else {
            die('Invalid ID');
        }
    } else {
        die('Only logged in user can like it.');
    }
}
function removeLike($data)
{
    $likeId = sanitize_text_field($data['like']);
    if (get_current_user_id() == get_post_field('post_author', $likeId) and get_post_type($likeId) == 'like') {
        wp_delete_post($likeId, true);
        return 'Congrats! Like deleted.';
    } else {
        die('You do not have permission to delete that.');
    }
}