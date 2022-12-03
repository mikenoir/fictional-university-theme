<?php

get_header();

while(have_posts()) {
    the_post(); ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?=get_theme_file_uri('/images/ocean.jpg');?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">
                <p>Learn how the school of your dreams got started.</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a
                    class="metabox__blog-home-link"
                    href="<?=get_post_type_archive_link('program');?>">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    Programs Home
                </a>
                <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php
        $relatedProfessors = new WP_Query([
            'post_type'         => 'professor',
            'posts_per_page'    => -1,
            'post_status'       => 'publish',
            'orderby'           => 'title',
            'order'             => 'ASC',
            'meta_query'        => [
                [
                    'key'       => 'related_programs',
                    'compare'   => 'LIKE',
                    'value'     => '"' . get_the_ID() . '"'
                ]
            ]
        ]);
        if( $relatedProfessors->have_posts() ) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';
            echo '<ul class="professor-cards">';
            while( $relatedProfessors->have_posts() ) {
                $relatedProfessors->the_post(); ?>
                    <li class="professor-card__list-item">
                        <a class="professor-card" href="<?php the_permalink(); ?>">
                            <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>" alt="">
                            <span class="professor-card__name"><?php the_title(); ?></span>
                        </a>
                    </li>
                <?php
            }
            echo '</ul>';
        }

        wp_reset_postdata();

        $relatedEvents = new WP_Query([
            'post_type'         => 'event',
            'posts_per_page'    => 2,
            'post_status'       => 'publish',
            'orderby'           => 'meta_value_num',
            'meta_key'          => 'event_date',
            'order'             => 'ASC',
            'meta_query'        => [
                [
                    'key'       => 'related_programs',
                    'compare'   => 'LIKE',
                    'value'     => '"' . get_the_ID() . '"'
                ]
            ]
        ]);
        if( $relatedEvents->have_posts() ) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcomming ' . get_the_title() . ' Events</h2>';
            while( $relatedEvents->have_posts() ) {
                $relatedEvents->the_post();
                get_template_part('template-parts/content', get_post_type());
            }
        }
        wp_reset_postdata(); ?>
    </div>

    <?php
}
get_footer();
