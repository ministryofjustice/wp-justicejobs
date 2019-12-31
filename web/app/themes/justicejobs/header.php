<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta name="description" content=""/>
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PWKHW94');</script>
    <!-- End Google Tag Manager -->

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PWKHW94"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<header id="main-nav-hook" class="page-header container">
    <a href="<?php bloginfo('url'); ?>" aria-label="Back to Home" class="page-header__logo">
        <span class="screen-reader-text">Back to Home</span>
        <img
            width="188"
            height="28"
            src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/img/logo--white.svg"
            alt="Ministry of Justice Logo - this takes the user back to the homepage"
            aria-hidden="true"
        />
    </a>
    <button class="page-header__menu closed">
        <span>MENU<span>
    </button>
    <nav class="page-header__nav-wrap">
        <?php

        $defaults = array(
            'container' => false,
            'theme_location' => 'header-main-menu',
            'menu_class' => 'page-header__nav'
        );

        wp_nav_menu($defaults);

        ?>

    </nav>
    <a href="<?php bloginfo('url'); ?>/search-page/" class="btn btn--bw">Search & Apply</a>
</header>
<main id="main-content-hook">
