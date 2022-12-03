<?php get_header(); ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?=get_theme_file_uri('/images/ocean.jpg');?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">All Events</h1>
            <div class="page-banner__intro">
                <p>See what is going on in our world.</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <?php
        while( have_posts() ) {
            the_post();
            get_template_part('template-parts/content', get_post_type());
        }

        echo paginate_links(); ?>
        <hr class="section-break">
        <p>Looking for a recap of past events? <a href="<?=site_url('/past-events');?>">Check outour past events archive</a></p>
    </div>
<?php get_footer();
