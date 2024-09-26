<?php

function fictional_custom_route()
{
    register_rest_route('fictional/v1/', 'search', [
        'methods'   => WP_REST_SERVER::READABLE,
        'callback'  =>  'fictionalSearchResults'
    ]);
}
add_action('rest_api_init', 'fictional_custom_route');

function fictionalSearchResults($data)
{
    $professor = new WP_Query([
        'post_type' => ['post', 'page', 'professor', 'campus', 'event', 'program'],
        's'         => sanitize_text_field($data['term'])
    ]);
    $results = [
        'general'   => [],
        'professor' => [],
        'event'     => [],
        'campus'    => [],
        'program'   => []
    ];
    while ($professor->have_posts()) {
        $professor->the_post();

        if (get_post_type() == 'post' or get_post_type() == 'page') {
            array_push($results['general'], [
                'title'      => get_the_title(),
                'link'       => get_permalink(),
                'authorName' => get_author_name(),
                'type'       => get_post_type()
            ]);
        }
        if (get_post_type() == 'professor') {
            array_push($results['professor'], [
                'title' => get_the_title(),
                'link'  => get_permalink(),
                'image' => get_the_post_thumbnail_url()
            ]);
        }

        if (get_post_type() == 'event') {

            if (has_excerpt()) {
                $content =  get_the_excerpt();
            } else {
                $content = wp_trim_words(get_the_content(), 12);
            }
            $eventDate = new DateTime(get_field('event_date'));
            $month =  $eventDate->format('M');
            $date =  $eventDate->format('d');

            array_push($results['event'], [
                'title' => get_the_title(),
                'link'  => get_permalink(),
                'content'   => $content,
                'month' => $month,
                'date'  => $date,
            ]);
        }
        if (get_post_type() == 'campus') {
            array_push($results['campus'], [
                'title' => get_the_title(),
                'link'  => get_permalink()
            ]);
        }
        if (get_post_type() == 'program') {
            array_push($results['program'], [
                'title' => get_the_title(),
                'link'  => get_permalink(),
                'id'    => get_the_ID()
            ]);
        }
    }

    if ($results['program']) {
        $programsMetaQuery = [
            'relation' => 'OR'
        ];

        foreach ($results['program'] as $item) {
            array_push($programsMetaQuery, [
                'key'   => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $item['id'] . '"'
            ]);
        }

        $programRelation = new WP_Query([
            'post_type' => 'proefessor',
            'meta_query' => $programsMetaQuery
        ]);
        while ($programRelation->have_posts()) {
            $programRelation->the_post();
            if (get_post_type() == 'professor') {
                array_push($results['professor'], [
                    'title' => get_the_title(),
                    'link'  => get_permalink(),
                    'image' => get_the_post_thumbnail_url()
                ]);
            }
        }

        $results['professor'] = array_values(array_unique($results['professor'], SORT_REGULAR));
    }

    return $results;
}