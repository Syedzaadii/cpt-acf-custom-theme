<?php

require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');

function custom_rest_field()
{
    register_rest_field('post', 'authorName', [
        'get_callback' => function () {
            return get_author_name();
        }
    ]);
    register_rest_field('note', 'userNoteCount', [
        'get_callback' => function () {
            return count_user_posts(get_current_user_id(), 'note');
        }
    ]);
}
add_action('rest_api_init', 'custom_rest_field');

function theme_scripts()
{
    // CSS
    wp_enqueue_style('google-font', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('index', get_template_directory_uri() . '/build/index.css');
    wp_enqueue_style('style-index', get_template_directory_uri() . '/build/style-index.css');
    wp_enqueue_style('custom-css', get_stylesheet_uri());
    // JS
    wp_scripts()->add_data('jquery', 'group', 1);
    wp_scripts()->add_data('jquery-core', 'group', 1);
    wp_scripts()->add_data('jquery-migrate', 'group', 1);
    wp_enqueue_script('jquery');
    wp_enqueue_script('build-js', get_template_directory_uri() . '/build/index.js', ['jquery'], '1.0', true);

    wp_localize_script('build-js', 'fictionalData', [
        'root_url' => get_site_url(),
        'nonce'    => wp_create_nonce('wp_rest')
    ]);
}
add_action('wp_enqueue_scripts', 'theme_scripts');

// setup
function theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    register_nav_menus([
        'primary' => 'Primary Menu'
    ]);
}
add_action('after_setup_theme', 'theme_setup');

// manipulate default query     
function fictional_queries($query)
{
    if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) {

        $today = date('Ymd');
        $query->set('order', 'ASC');
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'event_date');
        $query->set('meta_query', [
            [
                'key'     => 'event_date',
                'compare' => '>=',
                'value'   => $today,
                'type'  => 'numeric'
            ]
        ]);
    }
}
add_action('pre_get_posts', 'fictional_queries');

//page banner
function page_banner($args = NULL)
{
    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }

    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('subtitle');
    }

    if (!isset($args['image'])) {
        if (get_field('banner_image') and !is_archive() and !is_home()) {
            $args['image'] = get_field('banner_image')['url'];
        } else {
            $args['image']  = get_theme_file_uri('/images/ocean.jpg');
        }
    }
?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['image']; ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>
<?php
}

// redirect subscriber to home
function redirectSubsToSite()
{
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 and $currentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}
add_action('admin_init', 'redirectSubsToSite');

// hide admin bar
function hideAdminBar()
{
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 and $currentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}
add_action('wp_loaded', 'hideAdminBar');

// customize login screen
add_action('login_headerurl', 'ourHeaderURL');
function ourHeaderURL()
{
    return site_url('/');
}

// CUSTOM Login CSS
add_action('login_enqueue_scripts', 'ourLoginCSS');
function ourLoginCSS()
{
    wp_enqueue_style('google-font', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('index', get_template_directory_uri() . '/build/index.css');
    wp_enqueue_style('style-index', get_template_directory_uri() . '/build/style-index.css');
}

// change login title
add_filter('login_headertitle', 'customHeaderTitle');
function customHeaderTitle()
{
    return get_bloginfo('name');
}

// set post to private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);
function makeNotePrivate($data, $postarr)
{
    if ($data['post_type'] == 'note') {

        if (count_user_posts(get_current_user_id(), 'note') > 4 and !$postarr['ID']) {
            die('You have reached note limit');
        }

        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if ($data['post_type'] == 'note' and $data['post_status'] != 'trash') {
        $data['post_status'] = "private";
    }
    return $data;
}
