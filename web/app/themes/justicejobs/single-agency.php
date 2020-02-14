<?php
/*
Template Name: Agency Template
Template Post Type: agency
*/
?>

<?php get_header(); ?>

<?php if (have_posts()) :
    while (have_posts()) :
        the_post(); ?>

        <section class="hero hero--agency">
            <div class="hero__img-block">
                <div
                    class="hero__img hero__img--desktop"
                    style="background-image: url('<?php the_field('agency_hero_desktop_image'); ?>');"
                ></div>
                <div
                    class="hero__img hero__img--mobile"
                    style="background-image: url('<?php the_field('agency_hero_mobile_image'); ?>');"
                ></div>
                <div class="hero__img_description">
                    <?php the_field('agency_hero_image_description'); ?>
                </div>
                <div class="hero__text-wrap">
                    <svg class="hero__arrow hero__arrow--top" width="37" height="24">
                        <use xlink:href="#icon-arrow--decor"></use>
                    </svg>
                    <ul class="breadcrumbs text-highlight">
                        <li><a href="<?php echo get_bloginfo('url'); ?>">Home</a></li>
                        <li>Agency</li>
                    </ul>
                    <h1 class="heading--lg"><span class="text-highlight"><?php the_title(); ?></span></h1>
                    <svg class="hero__arrow hero__arrow--bottom" width="37" height="24">
                        <use xlink:href="#icon-arrow--decor"></use>
                    </svg>
                </div>
                <div class="hero__badge">
                    <img
                        src="<?php echo get_field('agency_logo_black'); ?>"
                        width="164"
                        height="77"
                        alt="HM Courts & Tribunals Service"
                    />
                </div>
            </div>
            <!-- <div class="hero__search hero__search--agency">
              <h2 class="heading--sm">Roles at HMCTS</h2>
              <a href="search.html" class="btn btn--green btn--full">Search jobs</a>
            </div> -->
        </section>

        <div class="agency">
            <div class="agency__col">
                <div class="agency__text">
                    <a href="<?php echo get_bloginfo('url'); ?>#work" class="btn-back btn-back--agency">
                        <svg width="8" height="13">
                            <use xlink:href="#icon-arrow"></use>
                        </svg>
                        Back to Agencies
                    </a>
                    <?php the_field('agency_content'); ?>
                </div>
            </div>


            <div class="agency__col">
                <?php
                $add_carousel = get_field('add_vacancies_carousel');
                if ($add_carousel == 1) :
                    ?>
                    <div class="agency__carousel agency__carousel--full accessible-carousel">
                        <h3 class="heading--xs"><?php the_field('carousel_title'); ?></h3>

                        <?php if (have_rows('carousel')) : ?>
                            <div id="accessible-full-carousel">
                                <ul class="accessible-carousel__container">
                                    <?php while (have_rows('carousel')) :
                                        the_row();
                                        $image_url = get_sub_field('background_image');
                                        $title = get_sub_field('title');
                                        $subtitle = get_sub_field('subtitle');
                                        $more_link = get_sub_field('link');
                                        ?>
                                        <li
                                            class="accessible-carousel__slide"
                                            style="background-image: url('<?php echo $image_url; ?>');"
                                        >
                                            <div class="agency__polygon full">
                                                <div class="accessible-carousel__wrap">
                                                    <h3 class="heading--sm<?= (strlen($title) > 20 ? ' heading--sm-text' : '') ?>"><?php echo $title; ?></h3>
                                                    <p class="heading--xxs"><?php echo $subtitle; ?></p>

                                                    <a href="<?php echo esc_url($more_link['url']); ?>"
                                                       target="<?php echo $more_link['target']; ?>"
                                                       class="btn-secondary btn-secondary--light">
                                                        <?php echo esc_html($more_link['title']); ?>
                                                        <svg width="8" height="13">
                                                            <use xlink:href="#icon-arrow"></use>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (have_rows('bottom_block')) :
                    $popup_row = 'bottom';

                    $cur = 0;


                    while (have_rows('bottom_block')) :
                        the_row();
                        $bottom_block = get_sub_field('bottom');
                        $image_url_bottom = $bottom_block['background_image'];
                        $block_title = $bottom_block['block_title'];
                        $title_bottom = $bottom_block['title'];
                        $ispopup = get_sub_field('pop_up_block');
                        $bg_colour = 'background-color: ' . get_field('agency_colour');

                        if ($ispopup) {

                            $popup_link_text = get_sub_field('pop_up_find_out_more_text');

                            if(empty($popup_link_text)){
                                $popup_link_text = 'Find out more';
                            }

                            $more_link_bottom['url'] = '#';
                            $more_link_bottom['title'] = $popup_link_text;
                            $main_class = '';
                            $a_class = 'carousel-popup-open';
                            $data_index = 'data-index="' . $cur . '"';
                        } else {
                            $more_link_bottom = get_sub_field('bottom_link');
                            $main_class = 'even-darker-blue-bg';
                            $a_class = '';
                            $data_index = '';
                            $bg_colour = '';
                        }

                        ?>
                        <div
                            class="agency__featured<?= ' ' . $main_class ?>"
                            style="background-image: url('<?= $image_url_bottom; ?>');<?= $bg_colour ?>"
                            role="link"
                        >
                            <span class="heading--xs"><?= $block_title; ?></span>
                            <h3><?= $title_bottom; ?></h3>

                            <a href="<?= esc_url($more_link_bottom['url']); ?>"
                                target="<?php if (isset($more_link_bottom['target'])) {
                                    echo $more_link_bottom['target'];
                                        };
                                        ?>"
                                class="btn-secondary btn-secondary--light<?= ' ' . $a_class; ?>"
                                <?= $data_index; ?>
                            >
                                <?php
                                echo esc_html($more_link_bottom['title']); ?>
                                <svg width="8" height="13">
                                    <use xlink:href="#icon-arrow"></use>
                                </svg>
                            </a>
                        </div>
                        <?php
                        if ($ispopup) {
                            $cur++;
                        }
                    endwhile;
                endif; ?>

                <?php
                $add_link = get_field('add_link');
                if ($add_link == 1) :
                    $link = get_field('find-out-more_link');
                    if ($link) :
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        ?>
                        <a href="<?php echo esc_url($link_url); ?>" class="btn-big btn-big--green"
                           target="<?php echo esc_attr($link_target); ?>"
                           style="background-color: <?php echo get_field('agency_colour'); ?>"
                        >
                            Find out more about <?php echo $link_title; ?>
                            <svg width="14" height="26">
                                <use xlink:href="#icon-arrow"></use>
                            </svg>
                        </a>
                    <?php endif;
                endif; ?>
            </div>
        </div>

        <?php

        get_footer();
        get_template_part('content', 'videopopup');

        if (have_rows('bottom_block')) :
            while (have_rows('bottom_block')) :
                the_row();
                $ispopup = get_sub_field('pop_up_block');
                if ($ispopup) :
                    ?>
                    <div class="popup popup--carousel">
                        <div class="popup__block">
                            <?php if (have_rows('pop-up_story')) : ?>
                                <div class="">
                                    <?php while (have_rows('pop-up_story')) :
                                        the_row(); ?>
                                        <section class="popup__item">
                                            <header>
                                                <span
                                                    class="heading--xs "><?php the_sub_field('story_title'); ?></span>
                                                <h3 class="heading--sm"><?php the_sub_field('persons_name'); ?></h3>
                                            </header>
                                            <div class="popup__body">
                                                <div>
                                                    <img src="<?php the_sub_field('story_image'); ?>" alt=""/>
                                                </div>
                                                <div class="popup__text">
                                                    <?php the_sub_field('story_content'); ?>
                                                </div>
                                            </div>
                                        </section>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>
                            <button class="btn-close" role="button" aria-label="Close">
                                <svg width="33" height="33">
                                    <use xlink:href="#icon-close"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endwhile;
        endif; ?>

    <?php
    endwhile;
endif;
?>
