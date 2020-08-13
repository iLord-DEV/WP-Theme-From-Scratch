<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="http://gmpg.org/xfn/11">
	<!-- Favicons -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo bloginfo('stylesheet_directory') ?>/dist/assets/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo bloginfo('stylesheet_directory') ?>/dist/assets/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo bloginfo('stylesheet_directory') ?>/dist/assets/favicons/favicon-16x16.png">
	<link rel="manifest" href="<?php echo bloginfo('stylesheet_directory') ?>/dist/assets/favicons/site.webmanifest">
	<link rel="mask-icon" href="<?php echo bloginfo('stylesheet_directory') ?>/dist/assets/favicons/safari-pinned-tab.svg" color="#5bbad5">
	<link rel="shortcut icon" href="<?php echo bloginfo('stylesheet_directory') ?>/dist/assets/favicons/favicon.ico">
	<meta name="msapplication-TileColor" content="#ffc40d">
	<meta name="msapplication-config" content="<?php echo bloginfo('stylesheet_directory') ?>/dist/assets/favicons/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!-- Preload Font -->
	<link rel="preload" href="<?php // echo get_template_directory_uri() . '/assets/fonts/Theinhardt-Regular.woff2'?>" as="font" type="font/woff2" crossorigin>
<!-- Preload CSS -->
	<link rel="preload" href="<?php echo get_template_directory_uri() . '/dist/css/main.css'?>" as="style">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/dist/css/main.css'?>" media="print" onload="this.media='all'">
  <?php wp_head(); ?>

<!-- inspired by https://fontfaceobserver.com/ -->
	<style>
		/* body {
		font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
	}
		.fonts-loaded body {
		font-family: 'Theinhardt Regular';
	} */
	</style>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div class="global-wrapper">
		<header id="masthead" class="main-header">
						<?php
              // Get the nav menu based on $menu_name (same as 'theme_location' or 'menu' arg to wp_nav_menu)
            // This code based on wp_nav_menu's code to get Menu ID from menu slug
            wp_nav_menu( array('menu' => 'primary', 'items_wrap' => '<ul><li id="item-id"></li>%3$s</ul>' ));
            ?>
		</header>
