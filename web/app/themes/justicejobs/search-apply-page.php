<?php
/*
Template Name: Search/Apply Template
*/
?>

<?php get_header(); ?>

<section class="hero hero--job hero--arrows">
  <div class="hero__img-block">
    <div
      class="hero__img hero__img--desktop"
      style="background-image: url('<?php the_field( 'hero_desktop_image' ); ?>');"
    ></div>
    <div
      class="hero__img hero__img--mobile"
      style="background-image: url('<?php the_field( 'hero_mobile_image' ); ?>');"
    ></div>
    <div class="hero__text-wrap">
      <svg class="hero__arrow hero__arrow--top" width="37" height="24">
        <use xlink:href="#icon-arrow--decor"></use>
      </svg>
      <ul class="breadcrumbs">
        <li><a href="<?php echo get_bloginfo( 'url' ); ?>">Home</a></li>
        <li>Search jobs</li>
      </ul>
      <h1 class="heading--lg">
        <?php the_title(); ?>
      </h1>
      <svg class="hero__arrow hero__arrow--bottom" width="37" height="24">
        <use xlink:href="#icon-arrow--decor"></use>
      </svg>
    </div>
  </div>
</section>


<div class="search_contain">
  <div id="allLocations" data-user-location='<?php echo $_location; ?>' data-relevant-terms=''>
    <?php
    $terms = get_terms( array(
        'taxonomy' => 'job_location',
        'hide_empty' => true,
    ) );
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
          $thisJobSite = get_field('the_job_location', $term); ?>
          <li data-name="<?php echo $term->name; ?>" data-id="<?php echo $term->term_id; ?>"  data-lat='<?php echo $thisJobSite['lat']; ?>' data-lng='<?php echo $thisJobSite['lng']; ?>'></li>
        <?php }
    }
  ?>
  </div>
  <div class="filter">
    <form action="#" id="search-form">
      <fieldset class="filter__fieldset">
        <div class="header">
          <legend class="heading--sm">Refine by:</legend>
          <button class="filter__reset" id="reset" role="button">Reset</button>
        </div>
        <label for="keyword" class="screen-reader-text">Keyword</label>
        <input aria-label="Keyword" type="text" class="input" placeholder="Keyword" name="keyword" id="keyword" />
        <span class="filter__label">Roles</span>
        <div class="dropdown">
          <div class="dropdown__wrap">
            <label for="role-type" class="screen-reader-text">Role Type</label>
            <input
              id="role-type"
              name="role-type"
              placeholder="Role Type"
              aria-label="Role Type"
              data-cur=""
              class="dropdown__current input"
              value=""
              readonly
            />
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="8"
              height="13"
              viewBox="0 0 7.6 11.52"
            >
              <path
                d="M7.6,5.71a1.61,1.61,0,0,1-.5,1.1l-4.6,4.3a1.27,1.27,0,0,1-1,.4A1.54,1.54,0,0,1,0,10a1.61,1.61,0,0,1,.5-1.1l1-1L4,5.71,1.6,3.41l-1-1A1.46,1.46,0,0,1,.83.36L.9.31A1.68,1.68,0,0,1,2.7.21l4.5,4.4A1.55,1.55,0,0,1,7.6,5.71Z"
              />
            </svg>
          </div>
          <ul class="dropdown__list">
            <li data-slug="all">All Role Types</li>
            <?php

            $terms = get_terms( array(
                'taxonomy' => 'role_type',
                'hide_empty' => true,
            ) );          if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                foreach ( $terms as $term ) {
                  echo '<li data-slug="' . $term->slug . '">' . $term->name . '</li>';
                }
            }

            ?>
          </ul>
        </div>
        <span class="filter__label">Location</span>
        <label for="location" class="screen-reader-text">Location</label>
        <input aria-label="Location" name="location" id="location" type="text" class="input" placeholder="City / Postcode" />
        <div class="dropdown">
          <div class="dropdown__wrap">
            <label for="radius" class="screen-reader-text">Radius (in miles)</label>
            <input
              id="radius"
              name='radius'
              aria-label="Radius (in miles)"
              data-cur="10"
              class="dropdown__current input"
              placeholder="Radius (miles)"
              readonly
            />
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="8"
              height="13"
              viewBox="0 0 7.6 11.52"
            >
              <path
                d="M7.6,5.71a1.61,1.61,0,0,1-.5,1.1l-4.6,4.3a1.27,1.27,0,0,1-1,.4A1.54,1.54,0,0,1,0,10a1.61,1.61,0,0,1,.5-1.1l1-1L4,5.71,1.6,3.41l-1-1A1.46,1.46,0,0,1,.83.36L.9.31A1.68,1.68,0,0,1,2.7.21l4.5,4.4A1.55,1.55,0,0,1,7.6,5.71Z"
              />
            </svg>
          </div>
          <ul class="dropdown__list">
            <li data-slug="5">5 Miles</li>
            <li data-slug="10">10 Miles</li>
            <li data-slug="25">25 Miles</li>
            <li data-slug="50">50 Miles</li>
          </ul>
        </div>
        <span class="filter__label">Salary Range</span>
        <div class="dropdown">
          <div class="dropdown__wrap">
            <label for="salary-range" class="screen-reader-text">Salary Range</label>
            <input
              id="salary-range"
              placeholder="Salary Range"
              name="salary-range"
              aria-label="Salary Range"
              data-cur=""
              class="dropdown__current input"
              value=""
              readonly
            />
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="8"
              height="13"
              viewBox="0 0 7.6 11.52"
            >
              <path
                d="M7.6,5.71a1.61,1.61,0,0,1-.5,1.1l-4.6,4.3a1.27,1.27,0,0,1-1,.4A1.54,1.54,0,0,1,0,10a1.61,1.61,0,0,1,.5-1.1l1-1L4,5.71,1.6,3.41l-1-1A1.46,1.46,0,0,1,.83.36L.9.31A1.68,1.68,0,0,1,2.7.21l4.5,4.4A1.55,1.55,0,0,1,7.6,5.71Z"
              />
            </svg>
          </div>
          <ul class="dropdown__list">
            <li data-slug="all">All Salary Ranges</li>
            <?php

            $terms = get_terms( array(
                'taxonomy' => 'salary_range',
                'hide_empty' => true,
            ) );          if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                foreach ( $terms as $term ) {
                  echo '<li data-slug=' . $term->slug . '>' . $term->name . '</li>';
                }
            }

            ?>
          </ul>
        </div>
        <span class="filter__label">Working Pattern</span>
        <div class="dropdown">
          <div class="dropdown__wrap">
            <label for="working-pattern" class="screen-reader-text">Working Pattern</label>
            <input
              id="working-pattern"
              name="working-pattern"
              placeholder="Working Pattern"
              aria-label="Working Pattern"
              data-cur=""
              class="dropdown__current input"
              value=""
              readonly
            />
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="8"
              height="13"
              viewBox="0 0 7.6 11.52"
            >
              <path
                d="M7.6,5.71a1.61,1.61,0,0,1-.5,1.1l-4.6,4.3a1.27,1.27,0,0,1-1,.4A1.54,1.54,0,0,1,0,10a1.61,1.61,0,0,1,.5-1.1l1-1L4,5.71,1.6,3.41l-1-1A1.46,1.46,0,0,1,.83.36L.9.31A1.68,1.68,0,0,1,2.7.21l4.5,4.4A1.55,1.55,0,0,1,7.6,5.71Z"
              />
            </svg>
          </div>
          <ul class="dropdown__list">
            <li data-slug="all">All Working Patterns</li>
            <?php

            $terms = get_terms( array(
                'taxonomy' => 'working_pattern',
                'hide_empty' => true,
            ) );          if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                foreach ( $terms as $term ) {
                  echo '<li data-slug=' . $term->slug . '>' . $term->name . '</li>';
                }
            }

            ?>
          </ul>
        </div>
        <button class="btn btn--blue" role="button" type="submit">Search jobs</button>
        </fieldset>
    </form>
  </div>
  <div class="search_contain__wrap">
    <label for="list-view" class="screen-reader-text">Select List View</label>
    <input
      class="search_contain__radio search_contain__radio--list"
      name="search"
      type="radio"
      id="list-view"
      aria-label="Select List View"
      aria-pressed="false"
    />
    <label for="map-view" class="screen-reader-text">Select Map View</label>
    <input
      class="search_contain__radio search_contain__radio--map"
      name="search"
      id="map-view"
      type="radio"
      aria-label="Select Map View"
      aria-pressed="true"
      checked
    />
    <header>

      <?php
      $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

      $args = array(
        'post_type' => 'job',
        'posts_per_page' => 10,
        'paged'          => $paged
      );
      $job_query = new WP_Query( $args );

      if ( $job_query->have_posts() ) {

      $pagenum = $job_query->query_vars['paged'] < 1 ? 1 : $job_query->query_vars['paged'];
      $first = ( ( $pagenum - 1 ) * $job_query->query_vars['posts_per_page'] ) + 1;
      $last = $first + $job_query->post_count - 1;
      echo "<span class='search_contain__results search_contain__results--live'>Showing <b>" . $first . " - " . $last . "</b> of <b>" . "$job_query->found_posts" . "</b> job results</span>";
       ?>
       <div class="pagination">
         <?php

         $big = 999999999; // need an unlikely integer
         echo paginate_links( array(
            'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
            'format' => '?paged=%#%',
            'mid_size' => 2,
            'current' => max( 1, get_query_var('paged') ),
            'total' => $job_query->max_num_pages,
            'prev_text' => '<span class="screen-reader-text">' . __('Search results - previous page', 'justicejobs') . '</span><span aria-hidden="true">' . __('PREV', 'justicejobs') . '</span>',
            'next_text' => '<span class="screen-reader-text"> ' . __('Search results', 'justicejobs') . ' -  </span>' . __('NEXT', 'justicejobs') . ' <span class="screen-reader-text">' . __('page', 'justicejobs') . '</span>',
            'before_page_number' => '<span class="screen-reader-text">' . __('Search results - page', 'justicejobs') . '</span>',
            'after_page_number' => '<span class="screen-reader-text"> ' . __(' of ', 'justicejobs') . __( $job_query->max_num_pages ) . '</span>'
            ) );
          ?>
       </div>

      <div class="search_contain__controls">
        <span>VIEW BY</span>
        <label class="search_contain__label search_contain__label--list" for="list-view">
          List
          <svg width="28" height="28">
            <use xlink:href="#icon-list"></use>
          </svg>
        </label>
        <label class="search_contain__label search_contain__label--map" for="map-view">
          Map
          <svg width="17" height="24">
            <use xlink:href="#icon-marker"></use>
          </svg>
        </label>
      </div>
    </header>
    <div class="search_contain__container">
      <div class="search_contain__list-wrap">
        <table class="search_contain__list">
          <tr class="search_contain__heading">
            <th>ROLE</th>
            <th>LOCATION</th>
            <th>SALARY</th>
            <th>WORKING PATTERN</th>
            <th>VIEW JOB</th>
          </tr>
          <?php
    				 while ( $job_query->have_posts() ) {
    					$job_query->the_post();
          ?>

          <tr class="search_contain__item">
            <td>
              <p><?php the_title(); ?></p>
            </td>
            <td>
              <p><?php the_field('location'); ?></p>
            </td>
            <td>
              <p>
              <?php
                $salary_copy = '';
                $terms = wp_get_post_terms($post->ID, 'salary_range', array("fields" => "all"));
                foreach ($terms as $term) {
                  $salary_copy = $salary_copy . $term->name . ', ';
                }
                echo substr($salary_copy, 0, -2);
               ?>
             </p>
            </td>
            <td>
              <p>
              <?php
                $working_pattern_copy = '';
                $terms = wp_get_post_terms($post->ID, 'working_pattern', array("fields" => "all"));
                foreach ($terms as $term) {
                  $working_pattern_copy = $working_pattern_copy . $term->name . ', ';
                }
                echo substr($working_pattern_copy, 0, -2);
              ?>
            </p>
            </td>
            <td>
              <a href="<?php the_permalink(); ?>" class="btn btn--blue btn--small">View</a>
            </td>
            <?php

            $terms = wp_get_post_terms($post->ID, 'job_location', array("fields" => "all"));
            foreach ($terms as $term) {
              $thisJobSite = get_field('the_job_location', $term);
              ?>
              <td class="marker" style="height:0; width: 0;" data-url="<?php the_permalink(); ?>" data-id="<?php the_ID(); ?>" data-lat="<?php echo $thisJobSite['lat']; ?>" data-lng="<?php echo  $thisJobSite['lng']; ?>" data-title="<?php echo get_the_title(); ?>"></td>
              <?php } ?>
          </tr>

          <?php
                }
           ?>

           <?php

             }
             wp_reset_postdata();

            ?>


        </table>
      </div>

      <div class="search_contain__map-wrap">
        <div
          class="map"
          id="map"
          data-zoom="14"
          style="width: 100%; height: 100%;"
        >
        </div>
      </div>
    </div>

    <footer>
      <div class="pagination">
        <?php

        $big = 999999999; // need an unlikely integer
         echo paginate_links( array(
            'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
            'format' => '?paged=%#%',
            'mid_size' => 2,
            'current' => max( 1, get_query_var('paged') ),
            'total' => $job_query->max_num_pages,
            'prev_text' => '<span class="screen-reader-text">' . __('Search results - previous page', 'justicejobs') . '</span><span aria-hidden="true">' . __('PREV', 'justicejobs') . '</span>',
            'next_text' => '<span class="screen-reader-text"> ' . __('Search results', 'justicejobs') . ' -  </span>' . __('NEXT', 'justicejobs') . ' <span class="screen-reader-text">' . __('page', 'justicejobs') . '</span>',
            'before_page_number' => '<span class="screen-reader-text">' . __('Search results - page', 'justicejobs') . '</span>',
            'after_page_number' => '<span class="screen-reader-text"> ' . __(' of ', 'justicejobs') . __( $job_query->max_num_pages ) . '</span>'
            ) );
         ?>
      </div>
    </footer>
  </div>
</div>




<?php get_footer(); ?>
