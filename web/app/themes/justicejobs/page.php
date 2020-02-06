<?php get_header(); ?>

<section class="hero hero--agency">
  <div class="hero__img-block">
    <div
      class="hero__img hero__img--desktop"
      style="background-image: url('<?php the_field( 'page_hero_desktop_image' ); ?>');"
    ></div>
    <div
      class="hero__img hero__img--mobile"
      style="background-image: url('<?php the_field( 'page_hero_mobile_image' ); ?>');"
    ></div>
    <div class="hero__img_description">
      <?php the_field( 'page_hero_image_description' ); ?>
    </div>
    <div class="hero__text-wrap">
      <svg class="hero__arrow hero__arrow--top" width="37" height="24">
        <use xlink:href="#icon-arrow--decor"></use>
      </svg>
      <ul class="breadcrumbs text-highlight">
        <li><a href="<?php echo get_bloginfo( 'url' ); ?>">Home</a></li>
        <li><?php echo the_field( 'page_category' ); ?></li>
      </ul>
      <h1 class="heading--lg"><span class="text-highlight"><?php the_title(); ?></span></h1>
      <svg class="hero__arrow hero__arrow--bottom" width="37" height="24">
        <use xlink:href="#icon-arrow--decor"></use>
      </svg>
    </div>
  </div>
</section>

<div class="page-content">
    <div class="page-content__wrap">
      <?php the_field('page_content'); ?>
    </div>
</div>


<?php get_footer(); ?>
