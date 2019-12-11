<?php get_header();
$search_query = get_search_query();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$_role_type = get_query_var('role-type');
$_salary_range = get_query_var('salary-range');
$_working_pattern = get_query_var('working-pattern');
$_location = get_query_var('location');
$_radius = (int)trim(get_query_var('radius'));
$_locations_relevant = get_query_var('locations-relevant');
$_locations_relevant_array = explode(',', $_locations_relevant);
$_locations_relevant_array_pop = array_pop($_locations_relevant_array);

?>


<section class="hero hero--job hero--arrows">
    <div class="hero__img-block">
        <div
            class="hero__img hero__img--desktop"
            style="background-image: url('<?php the_field('hero_desktop_image', 1870); ?>');"
        ></div>
        <div
            class="hero__img hero__img--mobile"
            style="background-image: url('<?php the_field('hero_mobile_image', 1870); ?>');"
        ></div>
        <div class="hero__text-wrap">
            <svg class="hero__arrow hero__arrow--top" width="37" height="24">
                <use xlink:href="#icon-arrow--decor"></use>
            </svg>
            <ul class="breadcrumbs">
                <li><a href="<?php echo get_bloginfo('url'); ?>">Home</a></li>
                <li>Search jobs</li>
            </ul>
            <h1 class="heading--lg">
                Search Results
            </h1>
            <svg class="hero__arrow hero__arrow--bottom" width="37" height="24">
                <use xlink:href="#icon-arrow--decor"></use>
            </svg>
        </div>
    </div>
</section>


<div class="search">
    <div id="allLocations" data-user-location='<?php echo $_location; ?>' data-relevant-terms=''>
        <?php
        $terms = get_terms(array(
            'taxonomy' => 'job_location',
            'hide_empty' => true,
        ));
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $thisJobSite = get_field('the_job_location', $term); ?>
                <li data-name="<?php echo $term->name; ?>" data-id="<?php echo $term->term_id; ?>"
                    data-lat='<?php echo $thisJobSite['lat']; ?>' data-lng='<?php echo $thisJobSite['lng']; ?>'></li>
            <?php }
        }
        ?>
    </div>
    <div class="filter">
        <header>
            <h2 class="heading--sm">Refine by:</h2>
            <button class="filter__reset" id="reset" role="button">Reset</button>
        </header>
        <form action="#" id="search-form">
            <label for="keyword" class="screen-reader-text">Keyword</label>
            <input aria-label="Keyword" type="text" class="input" placeholder="Keyword" name="keyword" id="keyword"
                   value="<?php echo $search_query; ?>"/>
            <span class="filter__label">Roles</span>
            <div class="dropdown">
                <div class="dropdown__wrap">
                    <label for="role-type" class="screen-reader-text">Role Type</label>
                    <input
                        id="role-type"
                        name="role-type"
                        aria-label="Role Type"
                        data-cur="<?php echo $_role_type; ?>"
                        class="dropdown__current input"
                        value=""
                        placeholder="Role Type"
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

                    $terms = get_terms(array(
                        'taxonomy' => 'role_type',
                        'hide_empty' => true,
                    ));
                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            echo '<li data-slug="' . $term->slug . '">' . $term->name . '</li>';
                        }
                    }

                    ?>
                </ul>
            </div>
            <span class="filter__label">Location</span>
            <label for="location" class="screen-reader-text">Location</label>
            <input id="location" aria-label="Location" name="location" type="text" class="input"
                   placeholder="City / Postcode" value="<?php echo $_location; ?>"/>
            <div class="dropdown">
                <div class="dropdown__wrap">
                    <label for="radius" class="screen-reader-text">Radius (in miles)</label>
                    <input
                        id="radius"
                        name='radius'
                        aria-label="Radius (in miles)"
                        data-cur="<?php if (!empty($_radius)) {
                            echo (int)trim($_radius);
                        } else {
                            echo 10;
                        } ?>"
                        class="dropdown__current input"
                        value="<?php if (!empty($_radius)) {
                            echo (int)trim($_radius) . ' Miles';
                        } else {
                            echo '';
                        } ?>"
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
                        name="salary-range"
                        aria-label="Salary Range"
                        data-cur="<?php echo $_salary_range; ?>"
                        class="dropdown__current input"
                        value=""
                        placeholder="Salary Range"
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

                    $terms = get_terms(array(
                        'taxonomy' => 'salary_range',
                        'hide_empty' => true,
                    ));
                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
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
                        aria-label="Working Pattern"
                        data-cur="<?php echo $_working_pattern; ?>"
                        class="dropdown__current input"
                        placeholder="Working Pattern"
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

                    $terms = get_terms(array(
                        'taxonomy' => 'working_pattern',
                        'hide_empty' => true,
                    ));
                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            echo '<li data-slug=' . $term->slug . '>' . $term->name . '</li>';
                        }
                    }

                    ?>
                </ul>
            </div>
            <button class="btn btn--blue" role="button">Search jobs</button>
        </form>
    </div>
    <div class="search__wrap">
        <label for="list-view" class="screen-reader-text">Select List View</label>
        <input
            class="search__radio search__radio--list"
            name="search"
            type="radio"
            id="list-view"
            aria-label="Select List View"
            aria-pressed="false"
        />
        <label for="map-view" class="screen-reader-text">Select Map View</label>
        <input
            class="search__radio search__radio--map"
            name="search"
            id="map-view"
            type="radio"
            aria-label="Select Map View"
            aria-pressed="true"
            checked
        />
        <header>
            <?php

            if (!empty($_role_type) && !empty($_salary_range) && !empty($_working_pattern) && !empty($_locations_relevant)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'role_type',
                            'field' => 'slug',
                            'terms' => $_role_type,
                        ),
                        array(
                            'taxonomy' => 'salary_range',
                            'field' => 'slug',
                            'terms' => $_salary_range,
                        ),
                        array(
                            'taxonomy' => 'working_pattern',
                            'field' => 'slug',
                            'terms' => $_working_pattern,
                        ),
                        array(
                            'taxonomy' => 'job_location',
                            'field' => 'term_id',
                            'terms' => $_locations_relevant_array,
                            'operator' => 'IN',
                        )
                    ),
                );
            } elseif (!empty($_role_type) && !empty($_salary_range) && !empty($_locations_relevant)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'role_type',
                            'field' => 'slug',
                            'terms' => $_role_type,
                        ),
                        array(
                            'taxonomy' => 'salary_range',
                            'field' => 'slug',
                            'terms' => $_salary_range,
                        ),
                        array(
                            'taxonomy' => 'job_location',
                            'field' => 'term_id',
                            'terms' => $_locations_relevant_array,
                            'operator' => 'IN',
                        )
                    ),
                );
            } elseif (!empty($_role_type) && !empty($_working_pattern) && !empty($_locations_relevant)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'role_type',
                            'field' => 'slug',
                            'terms' => $_role_type,
                        ),
                        array(
                            'taxonomy' => 'salary_range',
                            'field' => 'slug',
                            'terms' => $_salary_range,
                        ),
                        array(
                            'taxonomy' => 'job_location',
                            'field' => 'term_id',
                            'terms' => $_locations_relevant_array,
                            'operator' => 'IN',
                        )
                    ),
                );
            } elseif (!empty($_salary_range) && !empty($_working_pattern) && !empty($_locations_relevant)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'salary_range',
                            'field' => 'slug',
                            'terms' => $_salary_range,
                        ),
                        array(
                            'taxonomy' => 'working_pattern',
                            'field' => 'slug',
                            'terms' => $_working_pattern,
                        ),
                        array(
                            'taxonomy' => 'job_location',
                            'field' => 'term_id',
                            'terms' => $_locations_relevant_array,
                            'operator' => 'IN',
                        )
                    ),
                );
            } elseif (!empty($_role_type) && !empty($_salary_range)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'role_type',
                            'field' => 'slug',
                            'terms' => $_role_type,
                        ),
                        array(
                            'taxonomy' => 'salary_range',
                            'field' => 'slug',
                            'terms' => $_salary_range,
                        ),
                    ),
                );
            } elseif (!empty($_role_type) && !empty($_working_pattern)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'role_type',
                            'field' => 'slug',
                            'terms' => $_role_type,
                        ),
                        array(
                            'taxonomy' => 'working_pattern',
                            'field' => 'slug',
                            'terms' => $_working_pattern,
                        ),
                    ),
                );
            } elseif (!empty($_salary_range) && !empty($_working_pattern)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'salary_range',
                            'field' => 'slug',
                            'terms' => $_salary_range,
                        ),
                        array(
                            'taxonomy' => 'working_pattern',
                            'field' => 'slug',
                            'terms' => $_working_pattern,
                        ),
                    ),
                );
            } elseif (!empty($_role_type) && !empty($_locations_relevant)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'role_type',
                            'field' => 'slug',
                            'terms' => $_role_type,
                        ),
                        array(
                            'taxonomy' => 'job_location',
                            'field' => 'term_id',
                            'terms' => $_locations_relevant_array,
                            'operator' => 'IN',
                        )
                    ),
                );
            } elseif (!empty($_salary_range) && !empty($_locations_relevant)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'salary_range',
                            'field' => 'slug',
                            'terms' => $_salary_range,
                        ),
                        array(
                            'taxonomy' => 'job_location',
                            'field' => 'term_id',
                            'terms' => $_locations_relevant_array,
                            'operator' => 'IN',
                        )
                    ),
                );
            } elseif (!empty($_working_pattern) && !empty($_locations_relevant)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'working_pattern',
                            'field' => 'slug',
                            'terms' => $_working_pattern,
                        ),
                        array(
                            'taxonomy' => 'job_location',
                            'field' => 'term_id',
                            'terms' => $_locations_relevant_array,
                            'operator' => 'IN',
                        )
                    ),
                );
            } elseif (!empty($_locations_relevant)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'job_location',
                            'field' => 'term_id',
                            'terms' => $_locations_relevant_array,
                            'operator' => 'IN',
                        ),
                    ),
                );
            } elseif (!empty($_role_type)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'role_type',
                            'field' => 'slug',
                            'terms' => $_role_type,
                        ),
                    ),
                );
            } elseif (!empty($_salary_range)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'salary_range',
                            'field' => 'slug',
                            'terms' => $_salary_range,
                        ),
                    ),
                );
            } elseif (!empty($_working_pattern)) {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'working_pattern',
                            'field' => 'slug',
                            'terms' => $_working_pattern,
                        ),
                    ),
                );
            } else {
                $args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 10,
                    'paged' => $paged,
                    's' => $search_query
                );
            }


            $job_query = new WP_Query($args);

            if ($job_query->have_posts()) {

            $pagenum = $job_query->query_vars['paged'] < 1 ? 1 : $job_query->query_vars['paged'];
            $first = (($pagenum - 1) * $job_query->query_vars['posts_per_page']) + 1;
            $last = $first + $job_query->post_count - 1;
            echo "<span class='search__results search__results--live'>Showing <b>" . $first . " - " . $last . "</b> of <b>" . "$job_query->found_posts" . "</b> job results</span>";
            ?>
            <div class="pagination">
                <?php

                $big = 999999999; // need an unlikely integer
                echo paginate_links(array(
                    'base' => str_replace($big, '%#%', get_pagenum_link($big, false)),
                    'format' => '?paged=%#%',
                    'mid_size' => 2,
                    'current' => max(1, get_query_var('paged')),
                    'total' => $job_query->max_num_pages,
                    'prev_text' => 'PREV',
                    'next_text' => 'NEXT'
                ));


                ?>
            </div>

            <div class="search__controls">
                <span>VIEW BY</span>
                <label class="search__label search__label--list" for="list-view">
                    List
                    <svg width="28" height="28">
                        <use xlink:href="#icon-list"></use>
                    </svg>
                </label>
                <label class="search__label search__label--map" for="map-view">
                    Map
                    <svg width="17" height="24">
                        <use xlink:href="#icon-marker"></use>
                    </svg>
                </label>
            </div>
        </header>
        <div class="search__container">

            <div class="search__list-wrap">
                <table class="search__list">
                    <tr class="search__heading">
                        <th>ROLE</th>
                        <th>LOCATION</th>
                        <th>SALARY</th>
                        <th>WORKING PATTERN</th>
                        <th>VIEW JOB</th>
                    </tr>
                    <?php
                    while ($job_query->have_posts()) {
                        $job_query->the_post();
                        ?>

                        <tr class="search__item" data-locations="<?php
                        $terms = wp_get_post_terms($post->ID, 'job_location', array("fields" => "all"));
                        foreach ($terms as $term) {
                            echo $term->name;
                            echo ";";
                        }
                        ?>">
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
                                <td class="marker" style="height:0; width: 0;" data-url="<?php the_permalink(); ?>"
                                    data-id="<?php the_ID(); ?>" data-lat="<?php echo $thisJobSite['lat']; ?>"
                                    data-lng="<?php echo $thisJobSite['lng']; ?>"
                                    data-title="<?php echo get_the_title(); ?>"></td>
                            <?php } ?>
                        </tr>

                        <?php
                    }

                    ?>


                </table>
            </div>

            <div class="search__map-wrap">
                <div
                    class="map"
                    id="map"
                    data-zoom="14"
                    style="width: 100%; height: 100%;"
                >
                </div>
            </div>
        </div>
        <?php

        } else { ?>

            <div class="search__empty">
                <p class="heading--sm">Sorry no results found</p>
                <span>Please try again</span>
            </div>

        <?php }
        wp_reset_postdata();

        ?>

        <footer>
            <div class="pagination">

                <?php

                $big = 999999999; // need an unlikely integer
                echo paginate_links(array(
                    'base' => str_replace($big, '%#%', get_pagenum_link($big, false)),
                    'format' => '?paged=%#%',
                    'mid_size' => 2,
                    'current' => max(1, get_query_var('paged')),
                    'total' => $job_query->max_num_pages,
                    'prev_text' => 'PREV',
                    'next_text' => 'NEXT'
                ));


                ?>
            </div>
            <!--
           <ul class="pagination">
             <li><a class="current" href="#">1</a></li>
             <li><a href="#">2</a></li>
             <li><a href="#">3</a></li>
             <li><a href="#">4</a></li>
             <li>
               <a class="next" href="#">
                 NEXT
                 <svg width="9" height="13">
                   <use xlink:href="#icon-arrow"></use>
                 </svg>
               </a>
             </li>
           </ul>
           -->
        </footer>
    </div>
</div>


<?php get_footer(); ?>
