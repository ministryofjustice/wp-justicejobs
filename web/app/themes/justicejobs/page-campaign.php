<?php
/*
Template Name: Campaign Template
Template Post Type: page, campaign
*/

?>
<?php get_header(); ?>

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
        <use xlink:href="#icon-arrow--decor"></use>
      </svg>
      <ul class="breadcrumbs">
        <li><a href="<?php echo get_bloginfo('url'); ?>">Home</a></li>
        <li>Campaign</li>
      </ul>
      <h1 class="heading--lg"><?php the_title(); ?></h1>
      <p>
        <?php the_field('hero_text'); ?>
      </p>
      <svg class="hero__arrow hero__arrow--bottom" width="37" height="24">
        <use xlink:href="#icon-arrow--decor"></use>
      </svg>
    </div>
  </div>
</section>

<div class="campaign ">

  <?php

    $back_link = get_field('back_button');

    if ($back_link) :
        $link_url = $back_link['url'];
        $link_title = $back_link['title'];
        $link_target = $back_link['target'] ? $back_link['target'] : '_self';
        ?>
    <a href="<?php echo esc_url($link_url); ?>" class="btn-back btn-back--agency" target="<?php echo esc_attr($link_target); ?>">
      <svg width="8" height="13">
        <use xlink:href="#icon-arrow"></use>
      </svg>
        <?php echo esc_html($link_title); ?>
    </a>
    <?php endif; ?>

  <?php
    $add_overview = get_field('add_overview_section');
    if ($add_overview == 1) :
        $overview = get_field('overview_fields');
        $overview_title = $overview['overview_title'];
        $overview_content = $overview['overview_content'];
        $overview_video_url = $overview['video_url'];
        $overview_video_poster = $overview['video_poster'];
        ?>
  <div class="container">
        <?php
        $overview_link = get_field('overview_link');
        if ($overview_link) :
            $overview_link_url = $overview_link['url'];
            $overview_link_title = $overview_link['title'];
            $overview_link_target = $overview_link['target'];
            ?>
        <a href="<?php echo $overview_link_url; ?>" class="btn-back"
          target = "<?php echo $overview_link_target; ?>">
          <svg width="8" height="13">
            <use xlink:href="#icon-arrow"></use>
          </svg>
            <?php echo $overview_link_title; ?>
        </a>
        <?php endif; ?>
      <div class="campaign--container campaign__intro-block">
        <div class="campaign__text-col">
          <h2 class="heading--md">
            <?php echo $overview_title; ?>
          </h2>
          <?php echo $overview_content; ?>
        </div>

        <div class="campaign--container campaign__video">
        <?php
      
        // Video - ACF Campagin fields, people subfield
        if (have_rows('people')) :
            while (have_rows('people')) :
                the_row();
                the_sub_field('campaign_people_video');
            endwhile;
        else :
              echo '<!-- No video to display -->';
        endif;

        ?>
      </div>
      
        </div>
      </div>
    </div>
    <?php endif; ?>

  <?php
    $add_accordion = get_field('add_accordion_section');
    if ($add_accordion == 1) :
        ?>
  <div class="campaign__accordion">
    <h2 class="heading--md"><?php the_field('accordion_section_title');?></h2>
    <div class="accordion">
        <?php

        if (have_rows('accordion')) :
            while (have_rows('accordion')) :
                the_row();
                    $accordion_title = get_sub_field('accordion_title');
                    $accordion_title = sanitize_title_with_dashes($accordion_title);
                ?>
      <div class="accordion__block">
        <div class="campaign--container">
          <button
              class="accordion__btn"
              aria-controls="accordion-<?= $accordion_title ?>"
              aria-expanded="false"
              id="jj-<?= $accordion_title ?>">
            <h3><?php the_sub_field('accordion_title'); ?></h3>
            <span class="btn-plus">
              <svg width="30" height="30">
                <use xlink:href="#icon-plus"></use>
              </svg>
            </span>
          </button>
          <div
              class="accordion__content-wrap"
              id="accordion-<?= $accordion_title ?>"
              aria-labelledby="jj-<?= $accordion_title ?>"
              role="region">
                <?php the_sub_field('accordion_content'); ?>

                <?php
                $add_quote = get_sub_field('add_quote');
                if ($add_quote == 1) :
                    $quote = get_sub_field('accordion_quote');
                    if ($quote['quote_text']) :
                        ?>
            <div class="accordion__quote">
              <blockquote>
                “<?php echo $quote['quote_text'];?>”
                <cite class="heading--xs">
                  <b><?php echo $quote['quote_author']; ?></b>
                  <span><?php echo $quote['quote_author_position']; ?></span>
                </cite>
              </blockquote>
            </div>
                    <?php endif;
                endif; ?>

                <?php
                $add_table = get_sub_field('add_table');
                if ($add_table) :
                    if (have_rows('table')) :
                        $row_count = 0;
                        ?>
            <table>
                        <?php while (have_rows('table')) :
                            the_row();
                            if ($row_count == 0) :
                                ?>
                  <tr>
                    <th></th>
                    <th class="col_heading"><?php the_sub_field('cell_2'); ?></th>
                                <?php if (get_sub_field('cell_3')) : ?>
                      <th class="col_heading"><?php the_sub_field('cell_3'); ?></th>
                                    <?php if (get_sub_field('cell_4')) : ?>
                        <th class="col_heading"><?php the_sub_field('cell_4'); ?></th>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php
                                $row_count ++;
                            else :
                                if (get_sub_field('use_row_header')) {
                                    $td_class = "row_heading";
                                } else {
                                    $td_class = " ";
                                }
                                ?>
                  <tr>
                    <td class="<?php echo $td_class; ?>"><?php the_sub_field('cell_1'); ?></td>
                    <td><?php the_sub_field('cell_2'); ?></td>
                                <?php if (get_sub_field('cell_3')) : ?>
                      <td><?php the_sub_field('cell_3'); ?></td>
                                <?php endif; ?>
                                <?php if (get_sub_field('cell_4')) : ?>
                      <td><?php the_sub_field('cell_4'); ?></td>
                                <?php endif; ?>
                  </tr>
                            <?php endif; ?>
                            <?php $row_count ++;
                        endwhile; ?>
              </table>
                    <?php endif;
                endif ?>

                <?php $add_accordion = get_sub_field('add_inner_accordion');
                if ($add_accordion) :
                    $accordion_group = get_sub_field('inner');
                    $accordion_inner_title = $accordion_group['accordion_title'];
                    $accordion_inner_title = sanitize_title_with_dashes($accordion_inner_title);
                    ?>
              <div class="accordion">
                <div class="inner_accordion__block">
                  <div class="campaign--container inner_accordion--container">
                    <button
                        class="inner_accordion__btn"
                        aria-controls="accordion-<?= $accordion_inner_title ?>"
                        aria-expanded="false"
                        id="jj-<?= $accordion_inner_title ?>">
                      <span><?php echo $accordion_group['accordion_title']; ?></span>
                      <span class="inner_btn-plus">
                        <svg width="30" height="30">
                          <use xlink:href="#icon-plus"></use>
                        </svg>
                      </span>
                    </button>
                    <div
                        class="inner_accordion__content-wrap"
                        id="accordion-<?= $accordion_inner_title ?>"
                        aria-labelledby="jj-<?= $accordion_inner_title ?>"
                        role="region">>
                      <?php echo $accordion_group['accordion_content']; ?>
                    </div>
                  </div>
                </div>
              </div>

                <?php endif; ?>

          </div>
        </div>
      </div>
            <?php endwhile;
        endif; ?>
    </div>
  </div>
    <?php endif; ?>

  <?php
    $add_people_stories = get_field('add_people_stories_section');
    if ($add_people_stories == 1) :
        ?>
  <div class="campaign__text-wrap container">
    <h2 class="heading--md"><?php the_field('people_stories_section_title');?></h2>
    <p>
        <?php the_field('description'); ?>
    </p>
  </div>
  <div class="campaign__carousel-wrap campaign--container">
        <?php if (have_rows('people')) :
            $cur_carousel = 0;
            ?>
    <div class="campaign__carousel">
            <?php
            while (have_rows('people')) :
                the_row();
                $position = get_sub_field('job_position');
                $location = get_sub_field('job_location');
                $name = get_sub_field('name');
                $photo = get_sub_field('photo');
                $popup = get_sub_field('pop-up');

                if ($popup == "Pop-up Carousel") :
                    $a_class = "campaign__people campaign-carousel-popup-open";
                    ?>
          <div class="campaign__slide">
            <a
              href="#"
              class="<?php echo $a_class; ?>"
              data-index="<?php echo $cur_carousel; ?>"
              style="background-image: url('<?php echo $photo; ?>');"
            >
                <?php endif; ?>

              <div class="campaign__people-text">
                <span class="heading--xs"><?php echo $position; ?></span>
                <span class="heading--xs"><?php echo $location; ?></span>
                <h3><?php echo $name; ?></h3>
                <span class="btn-secondary btn-secondary--light">
                  <?php echo "Read more"; ?>
                  <svg width="8" height="13">
                    <use xlink:href="#icon-arrow"></use>
                  </svg>
                </span>
              </div>
            </a>
          </div>
                <?php
                if ($popup == "Pop-up Carousel") {
                    $cur_carousel++;
                }
            endwhile;
            ?>
      </div>

      <div class="carousel__dots"></div>
      <div class="carousel__controls">
        <button class="carousel__arrow carousel__arrow--prev" role="button" aria-label="Previous Video">
          <svg width="23" height="40">
            <use xlink:href="#icon-arrow--thin"></use>
          </svg>
        </button>
        <button class="carousel__arrow carousel__arrow--next" role="button" aria-label="Next Video">
          <svg width="23" height="40">
            <use xlink:href="#icon-arrow--thin"></use>
          </svg>
        </button>
      </div>
    </div>
        <?php endif; ?>
    <?php endif; ?>

<?php
$add_apply_section = get_field('add_apply_section');
if ($add_apply_section == 1) :
    ?>
  <div class="campaign__text-wrap container">
    <h2 class="heading--md"><?php the_field('apply_section_title'); ?></h2>
    <p>
      <?php the_field('apply_section_description'); ?>
    </p>
    <?php
      $apply_link = get_field('apply_link');
    if ($apply_link) :
        $apply_link_url = $apply_link['url'];
        $apply_link_title = $apply_link['title'];
        $apply_link_target = $apply_link['target'] ? $apply_link['target'] : '_self';
        ?>
    <a href="<?php echo esc_url($apply_link_url); ?>" class="btn btn--blue"
      target="<?php echo esc_attr($apply_link_target); ?>"
    >
        <?php echo $apply_link_title; ?>
    </a>
    <?php endif; ?>
  </div>
<?php endif; ?>
  <?php
    $add_link = get_field('add_link');
    if ($add_link == 1) :
        $link = get_field('find-out-more_link');
        if ($link) :
            $link_url = $link['url'];
            $link_title = $link['title'];
            $link_target = $link['target'] ? $link['target'] : '_self';
            ?>
  <a href="<?php echo esc_url($link_url); ?>" class="btn-big"
    target="<?php echo esc_attr($link_target); ?>"
  >
    Find out more about <?php echo $link_title; ?>
    <svg width="14" height="26">
      <use xlink:href="#icon-arrow"></use>
    </svg>
  </a>
        <?php endif;
    endif;?>
</div>

<?php get_footer(); ?>

<?php if (have_rows('people')) :
    while (have_rows('people')) :
        the_row();
        $popup = get_sub_field('pop-up');
        if ($popup == "Pop-up Carousel") :
            $group = get_sub_field('campaign_pop-up_carousel');
            $carousel_rows = $group['pop-up_carousel'];
            ?>
    <div class="popup popup--carousel">
      <div class="popup__block">
        <div class="popup__carousel">

            <?php foreach ($carousel_rows as $row) : ?>
          <section class="popup__item">
            <header>
              <span class="heading--xs "><?php echo $row['carousel_category']; ?></span>
              <h3 class="heading--sm"><?php echo $row['carousel_title']; ?></h3>
            </header>
            <div class="popup__body">
              <div>
                <img src="<?php echo $row['carousel_image']; ?>" alt="" />
              </div>
              <div>
                <?php echo $row['carousel_content']; ?>
              </div>
            </div>
          </section>

            <?php endforeach; ?>

        </div>

        <button class="popup__arrow popup__arrow--prev" role="button" aria-label="Previous Person">
          <svg width="16" height="25">
            <use xlink:href="#icon-arrow"></use>
          </svg>
        </button>
        <button class="popup__arrow popup__arrow--next" role="button" aria-label="Next Person">
          <svg width="16" height="25">
            <use xlink:href="#icon-arrow"></use>
          </svg>
        </button>
        <div class="popup__dots carousel__dots"></div>
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
