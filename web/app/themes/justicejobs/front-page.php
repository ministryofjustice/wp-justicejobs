<?php get_header(); ?>

<section class="hero hero--home hero--arrows">
    <div class="hero__img-block">
        <div class="hero__img hero__img--desktop"
            style="background-image: url('<?php the_field('hero_desktop_image'); ?>');"
        ></div>
        <div class="hero__img hero__img--mobile"
            style="background-image: url('<?php the_field('hero_mobile_image'); ?>');"
        ></div>
        <div class="hero__img_description">
            <?php the_field('hero_image_description'); ?>
        </div>
        <svg class="hero__line" width="843" height="83">
            <use xlink:href="#icon-decor-line-2"></use>
        </svg>
        <div class="hero__text-wrap">
            <div class="hero__heading-wrap">
                <svg class="hero__arrow hero__arrow--top" width="37" height="24">
                    <use xlink:href="#icon-arrow--decor"></use>
                </svg>
                <h1 class="heading--lg">
                    <span class="front-page text-highlight"><?php the_field('hero_title'); ?></span>
                </h1>
                <svg
                    class="hero__arrow hero__arrow--bottom"
                    width="37"
                    height="24"
                >
                    <use xlink:href="#icon-arrow--decor"></use>
                </svg>
            </div>
            <p class="text-highlight">
                <span class="front-page text-highlight"><?php the_field('hero_text'); ?></span>
            </p>
            <!--<a href="<?php /*bloginfo('url'); */?>/search-page/" class="btn btn--bw search-page-link ga-nav-top-right">Search
                & Apply</a>-->
        </div>
    </div>

    <div class="hero__search">
        <div id="allLocations" data-relevant-terms=''>
            <?php
            $terms = get_terms(array(
                'taxonomy' => 'job_location',
                'hide_empty' => true,
            ));
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $thisJobSite = get_field('the_job_location', $term); ?>
                    <li data-name="<?php echo $term->name; ?>" data-id="<?php echo $term->term_id; ?>"
                        data-lat='<?php echo $thisJobSite['lat']; ?>'
                        data-lng='<?php echo $thisJobSite['lng']; ?>'></li>
                <?php }
            }
            ?>
        </div>
        <form id="mini-search-form">
            <fieldset class="hero__search--fieldset">
                <legend class="heading--sm">Quick job search</legend>
                <div class="row">
                    <label for="keyword" class="screen-reader-text">Keyword</label>
                    <input aria-label="Keyword" type="text" class="input" placeholder="Keyword" name="keyword"
                           id="keyword"/>
                </div>
                <div class="row">
                    <span>in</span>
                    <label for="location" class="screen-reader-text">Location</label>
                    <input id="location" name="location" aria-label="Location" type="text" class="input"
                           placeholder="City / Postcode"/>
                </div>
                <button class="btn btn--dark-blue btn--full search-page-link ga-mini-home-form-button" type="submit">
                    Search
                    jobs
                </button>
            </fieldset>
        </form>
    </div>


    <?php
    $add_carousel = get_field('add_latest_roles_carousel');
    if ($add_carousel == 1) :
        ?>
        <div class="hero__carousel">
            <div class="accessible-carousel" id="accessible-carousel">

                <?php if (have_rows('latest_roles_carousel')) : ?>
                    <ul class="accessible-carousel__container">
                        <?php while (have_rows('latest_roles_carousel')) :
                            the_row();
                            $agency_name = get_sub_field('agency_name');
                            $position_title = get_sub_field('position_title');
                            $position_location = get_sub_field(' position_location');
                            $more_link = get_sub_field('find-out-more_link');
                            ?>
                            <li class="accessible-carousel__slide slide">
                                <p class="heading--xxs"><?php echo $agency_name; ?></p>
                                <h3 class="heading--sm"><?php echo $position_title; ?></h3>
                                <p class="heading--xxs"><?php echo $position_location; ?></p>

                                <a href="<?php echo esc_url($more_link['url']); ?>"
                                   class="btn-secondary btn-secondary--light">
                                    <?php echo esc_html($more_link['title']); ?>
                                    <svg width="8" height="13">
                                        <use xlink:href="#icon-arrow"></use>
                                    </svg>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<div class="about" id="work">
    <div class="about__text-wrap">
        <h2 class="heading--md"><?php the_field('working_with_us_title'); ?></h2>
        <p>
            <?php $working_with_us_text = get_field('working_with_us_text');
            if ($working_with_us_text) {
                echo $working_with_us_text;
            }
            ?>
        </p>
    </div>
</div>

<?php
$args = array(
    'post_type' => 'agency',
    'orderby' => 'date',
    'order' => 'ASC'
);
$the_query = new WP_Query($args);


if ($the_query->have_posts()) : ?>
    <div class="about__container" id="agencies-grid">

        <?php while ($the_query->have_posts()) :
            $the_query->the_post();
            $post_id = get_the_id();

            $featured_img_url = get_field('agency_hero_desktop_image');
            $agency_name = get_the_title($post_id);
            $agency_colour = get_field('agency_colour');

            ?>

            <a
                href="<?= get_post_permalink($post_id); ?>"
                class="about__block"
                style="text-decoration:none; color:inherit; display:inline-block; background-color: <?= $agency_colour ?>;background-image: url('<?= $featured_img_url ?>');"
                aria-label="Find out more about the <?= $agency_name ?>"
            >

                <img
                    class="about__logo <?= get_post_field('post_name', $post_id) ?>"
                    src="<?= get_field('agency_logo_white'); ?>"
                    alt="<?= $agency_name ?> logo"
                />
                <div class="btn-secondary btn-secondary--light">
                    Find out more
                    <svg width="8" height="13">
                        <use xlink:href="#icon-arrow"></use>
                    </svg>
                </div>

            </a>


        <?php endwhile; ?>
    </div>

    <?php wp_reset_postdata();
endif; ?>

<div class="awards">
    <div class="awards__container">
        <div class="awards-carousel">
            <?php

            // Award gallery ACF field
            $images = get_field('award_gallery');

            if (is_array($images)) :
                foreach ($images as $image) : ?>
                    <div class="awards__slide">
                        <div class="awards__img-wrap">
                            <img src="<?php echo $image['url']; ?>"
                                 width="<?php echo $image['sizes']['thumbnail-width']; ?>"
                                 height="<?php echo $image['sizes']['thumbnail-height']; ?>"
                                 alt="<?php echo $image['alt']; ?>"/>
                        </div>
                    </div>
                <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>

</div>

<?php get_footer(); ?>
