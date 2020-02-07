<?php
/* Template Name: About Us Template */

get_header();
?>

<?php if (have_posts()) : while (have_posts()) :the_post(); ?>
    <section class="hero">
        <div class="hero__img-block">
            <div
                class="hero__img hero__img--desktop"
                style="background-image: url('<?php the_field('hero_desktop_image'); ?>');"
            ></div>
            <div
                class="hero__img hero__img--mobile"
                style="background-image: url('<?php the_field('hero_mobile_image'); ?>');"
            ></div>
            <div class="hero__img_description">
                <?php the_field('hero_image_description'); ?>
            </div>
            <div class="hero__text-wrap">
                <svg class="hero__arrow hero__arrow--top" width="37" height="24">
                    <use xlink:href="<?php echo esc_url(get_template_directory_uri()); ?>/img/icon-arrow--decor"></use>
                </svg>
                <ul class="breadcrumbs text-highlight">
                    <li><a href="<?php echo get_bloginfo('url'); ?>">Home</a></li>
                    <li><?php the_title(); ?></li>
                </ul>
                <h1 class="heading--lg"><span class="text-highlight"><?php the_title(); ?></span></h1>
                <p>
                    <span class="text-highlight"><?php the_field('hero_text'); ?></span>
                </p>
                <svg class="hero__arrow hero__arrow--bottom" width="37" height="24">
                    <use xlink:href="<?php echo esc_url(get_template_directory_uri()); ?>/img/icon-arrow--decor"></use>
                </svg>
            </div>
        </div>
    </section>

    <section class="overview" id="overview">

        <div class="container">
            <div class="overview__text-wrap">
                <div class="overview__text">
                    <h2 class="heading--md"><?php the_field('overview_title'); ?></h2>
                    <?php the_field('overview_content'); ?>
                </div>
            </div>

            <?php
            $about_us_video_url = get_field('about_us_video_oembed', false, false);

            if(empty($about_us_video_url) == false){

                $wrap_class = '';
                $video_title = get_field('overview_video_title');

                if(empty($video_title) == false){
                    $wrap_class = 'overview__video_has_title';
                }
            ?>
                <div class="overview__video-wrap <?php echo $wrap_class; ?>">
                    <div class="overview__video">
                        <?php if(empty($video_title) == false){ ?>
                            <h3 class="heading--sm"><?php echo $video_title; ?></h3>
                        <?php } ?>
                        <?php the_field('about_us_video_oembed'); ?>
                    </div>
                </div>
            <?php } ?>

        </div>

    </section>

    <section class="work">
        <div class="about__text-wrap">
            <h2 class="heading--md">Where we work</h2>
            <p> <?php echo the_field('agency_carousel_title'); ?> </p>
        </div>

        <div class="work__carousel accessible-carousel accessible-carousel--full-width">
            <div id="accessible-carousel">

                <?php $agencies = get_field('featured_agencies'); ?>

                <ul class="accessible-carousel__container">
                    <?php foreach ($agencies as $agency):

                        $agency_image = get_field('agency_hero_desktop_image', $agency->ID);
                        $agency_name = get_the_title($agency->ID);
                        $agency_colour = get_field('agency_colour', $agency->ID);
                        $agency_link = get_permalink($agency->ID);

                        $agency_description = get_field('homepage_description', $agency->ID);
                        $agency_quote_text = get_field('homepage_quote', $agency->ID);
                        $agency_quote_author = get_field('homepage_quote_author', $agency->ID);
                        $agency_quote_author_position = get_field('homepage_quote_author_position', $agency->ID);

                        ?>
                        <li class="accessible-carousel__slide slide">
                            <div class="accessible-carousel__wrap"
                                 style="background-image: url('<?php echo $agency_image; ?>');">
                                <h3 class="heading--md">
                                    <?php echo $agency_name; ?>
                                </h3>
                                <p class="heading--body">
                                    <?php echo $agency_description; ?>
                                </p>
                                <a
                                    href="<?php echo $agency_link; ?>"
                                    class="btn-secondary btn-secondary--light">
                                    Find out more
                                    <svg width="8" height="13">
                                        <use xlink:href="#icon-arrow"></use>
                                    </svg>
                                </a>
                            </div>
                            <div class="work__text-block"
                                 style="background-color: <?php echo $agency_colour; ?>;">
                                <div class="work__text-wrap">
                                    <svg width="37" height="24">
                                        <use xlink:href="#icon-arrow--decor"></use>
                                    </svg>
                                    <blockquote class="quote-copy">
                                        <?php echo $agency_quote_text; ?>
                                    </blockquote>
                                    <p class="work__cite-wrap heading--xxs">
                                        <span><?php echo $agency_quote_author; ?></span>
                                        <span><?php echo $agency_quote_author_position; ?></span>
                                    </p>
                                    <svg width="37" height="24">
                                        <use xlink:href="#icon-arrow--decor"></use>
                                    </svg>
                                </div>
                                <svg width="791" height="75">
                                    <use xlink:href="#icon-decor-line-1"></use>
                                </svg>
                            </div>
                        </li>

                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    </section>

    <div class="about__search-wrap">
        <?php $search_job_title = get_field('search_job_title');
        $search_job_text = get_field('search_job_text');
        if ($search_job_title): ?>
        <h2 class="heading--md"><?php echo $search_job_title;
            endif; ?></h2>
        <p>
            <?php if ($search_job_text) {
                echo $search_job_text;
            }
            ?>
        </p>
        <a href="/search-page/" class="btn btn--blue">Search jobs</a>
    </div>

<?php endwhile; endif; ?>

<?php
get_footer();
