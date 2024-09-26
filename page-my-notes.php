<?php
if (!is_user_logged_in()) {
    wp_redirect(site_url('/'));
    exit;
}
get_header();
page_banner([
    'title' => 'My Notes',
    'subtitle' => 'Learn how the school of your dreams got started.'
]);
?>
<div class="container container--narrow page-section">

    <div class="create-note">
        <h2 class="headline headline--medium">Create New Note</h2>
        <input type="text" placeholder="Title" class="new-note-title">
        <textarea name="" class="new-note-body" placeholder="Your note here..."></textarea>
        <span class="submit-note">Create Note</span>
        <span class="note-limit-message">Note Limit reached.</span>
    </div>

    <ul class="min-list link-list" id="mynotes">
        <?php
        $notes_query = new WP_Query([
            'post_type'      => 'note',
            'posts_per_page' => -1,
            'author'         => get_current_user_id()
        ]);
        while ($notes_query->have_posts()) :
            $notes_query->the_post();
        ?>
        <li data-id="<?php the_ID(); ?>">
            <input value="<?php echo str_replace('Private: ', '', esc_attr(get_the_title())); ?>"
                class="note-title-field" readonly>
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"> Edit</i></span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"> Delete</i></span>
            <textarea readonly
                class="note-body-field"><?php echo esc_textarea(wp_strip_all_tags(get_the_content())); ?></textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true">
                    Save</i></span>
        </li>

        <?php endwhile; ?>
    </ul>
</div>
<?php get_footer(); ?>