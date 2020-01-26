<?php get_header(); ?>

<?php if ( have_posts() ) : while( have_posts() ): the_post(); ?>

<section class="hero hero--job hero--arrows">
  <div class="hero__img-block">
    <div
      class="hero__img hero__img--desktop"
      style="background-image: url('<?php the_field( 'hero_desktop_image', 'option' ); ?>');"
    ></div>
    <div
      class="hero__img hero__img--mobile"
      style="background-image: url('<?php the_field( 'hero_mobile_image', 'option' ); ?>');"
    ></div>
    <div class="hero__img_description">
      <?php the_field( 'hero_image_description' ); ?>
    </div>
    <div class="hero__text-wrap">
      <svg class="hero__arrow hero__arrow--top" width="37" height="24">
        <use xlink:href="#icon-arrow--decor"></use>
      </svg>
      <ul class="breadcrumbs text-highlight">
        <li><a href="<?php echo get_bloginfo( 'url' ); ?>">Home</a></li>
        <li>Job</li>
      </ul>
      <h1 class="heading--lg">
        <?php // the_title(); ?>
        <span class="text-highlight"><?php //the_field( 'role_type' );
         $terms = wp_get_post_terms($post->ID, 'role_type', array("fields" => "all"));
         foreach ($terms as $term) {
           echo $term->name;
         }

        ?></span>
      </h1>
      <svg class="hero__arrow hero__arrow--bottom" width="37" height="24">
        <use xlink:href="#icon-arrow--decor"></use>
      </svg>
    </div>
  </div>
</section>

<div class="job">
  <div class="job__wrap">
    <h2 class="heading--sm">
      <?php the_title(); ?>
      <?php
      /*
      $terms = wp_get_post_terms($post->ID, 'role_type', array("fields" => "all"));
      foreach ($terms as $term) {
        echo $term->name;
      }
      */
       ?>
      <br />
      <?php

      $terms = wp_get_post_terms($post->ID, 'salary_range', array("fields" => "all"));
      foreach ($terms as $term) {
        echo $term->name;
      }

       ?>
      <br />
      <?php the_field('location'); ?>
    </h2>
    <div class="job__text-wrap">
      <header>
        <a href="<?php the_field('application_link'); ?>" class="btn btn--blue" target="_blank">Apply</a>
        <a class="btn-back btn-back--blue back-to-search">
          <svg width="8" height="13">
            <use xlink:href="#icon-arrow"></use>
          </svg>
          BACK TO SEARCH
        </a>
      </header>
      <div class="job__text">
        <?php
        the_content();
        ?>
        <h2>Additional Information</h2>
        <?php the_field('additional_information'); ?>

      </div>
      <footer>
        <a href="<?php the_field('application_link'); ?>" class="btn btn--blue" target="_blank">Apply</a>
        <a class="btn-back btn-back--blue back-to-search">
          <svg width="8" height="13">
            <use xlink:href="#icon-arrow"></use>
          </svg>
          BACK TO SEARCH
        </a>
      </footer>
    </div>
  </div>
  <!-- <a href="#" class="btn-big">
    Find out more about MOJ HQ
    <svg width="14" height="26">
      <use xlink:href="#icon-arrow"></use>
    </svg>
  </a> -->
</div>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
