<?php get_header(); ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?=get_theme_file_uri('/images/ocean.jpg');?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">Past Events</h1>
            <div class="page-banner__intro">
                <p>See what is going on in our world.</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <?php
        $pastEvents = new WP_Query([
            'post_type'         => 'event',
            'paged'             => get_query_var('paged', 1),
            'post_status'       => 'publish',
            'orderby'           => 'meta_value_num',
            'meta_key'          => 'event_date',
            'order'             => 'ASC',
            'meta_query'        => [
                [
                    'key'       => 'event_date',
                    'compare'   => '<',
                    'value'     => date('Ymd'),
                    'type'      => 'numeric'
                ]
            ]
        ]);
        while( $pastEvents->have_posts() ) {
            $pastEvents->the_post();
            get_template_part('template-parts/content', get_post_type());
        }

        echo paginate_links([
            'total' => $pastEvents->max_num_pages
        ]); ?>
    </div>
<?php get_footer();
