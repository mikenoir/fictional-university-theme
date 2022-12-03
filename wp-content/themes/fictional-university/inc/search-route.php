<?php

function university_register_search() {
    register_rest_route('university/v1', 'search', [
        'methods'   => WP_REST_Server::READABLE,
        'callback'  => 'university_search_results'
    ]);
}

add_action('rest_api_init', 'university_register_search');

function university_search_results( $data): array {

    $mainQuery = new WP_Query([
        'post_type'     => ['post', 'page', 'professor', 'program', 'campus', 'event'],
        's'             => sanitize_text_field($data['term'])
    ]);

    $results = [
        'generalInfo'   => [],
        'professor'    => [],
        'program'      => [],
        'event'        => [],
        'campus'      => []
    ];

    while( $mainQuery->have_posts() ) {
        $mainQuery->the_post();

        if( get_post_type() === 'post' || get_post_type() === 'page' ) {
            $results['generalInfo'][] = [
                'title'     => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType'  => get_post_type(),
                'authorName'    => get_the_author()
            ];
        } else {
            $month = get_the_date('M');
            $day = get_the_date('d');
            if( get_post_type() == 'event' ) {
                $eventDate = new DateTime( get_field('event_date') );
                $month = $eventDate->format('M');
                $day = $eventDate->format('d');
            }
            $results[get_post_type()][] = [
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'permalink' => get_the_permalink(),
                'thumbnail' => get_the_post_thumbnail_url(0, 'professorLandscape'),
                'month'     => $month,
                'day'       => $day,
                'content'   => get_the_excerpt()
            ];
        }
    }

    wp_reset_postdata();

    // This code is for relate professors if user search a program

    if( $results['program'] ) {
        $programsMetaQuery = [
            'relation'  => 'OR'
        ];

        foreach( $results['program'] as $program ) {
            $programsMetaQuery[] = [
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $program['id'] . '"'
            ];
        }

        $professorRelationshipQuery = new WP_Query([
            'post_type'     => ['professor', 'event'],
            'meta_query'    => $programsMetaQuery
        ]);

        while( $professorRelationshipQuery->have_posts() ) {
            $professorRelationshipQuery->the_post();

            $month = get_the_date('M');
            $day = get_the_date('d');
            if( get_post_type() == 'event' ) {
                $eventDate = new DateTime( get_field('event_date') );
                $month = $eventDate->format('M');
                $day = $eventDate->format('d');
            }

            $results[get_post_type()][] = [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'thumbnail' => get_the_post_thumbnail_url(0, 'professorLandscape'),
                'month'     => $month,
                'day'       => $day,
                'content'   => get_the_excerpt()
            ];
        }

        $results['professor'] = array_values(array_unique( $results['professor'], SORT_REGULAR ));
        $results['event'] = array_values(array_unique( $results['event'], SORT_REGULAR ));
    }

    return $results;
}