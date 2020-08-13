<?php

/**
 * traxpay functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package traxpay
 */

if ( ! function_exists( 'mystartertheme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function mystartertheme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on traxpay, use a find and replace
		 * to change 'mystartertheme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'mystartertheme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Main Menu', 'mystartertheme' ),
			
		) );

		
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );



		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

	}
endif;

add_action( 'after_setup_theme', 'mystartertheme_setup' );



/**
 * Enqueue scripts and styles.
 */
function mystartertheme_scripts() {
	// wp_enqueue_script( 'mystartertheme-navigation', get_template_directory_uri() . '/dist/js/navigation.js', array(), '20151215', true );
	// wp_enqueue_script( 'mystartertheme-skip-link-focus-fix', get_template_directory_uri() . '/dist/js/skip-link-focus-fix.js', array(), '20151215', true );
	wp_enqueue_script( 'mystartertheme-main', get_template_directory_uri() . '/dist/js/main.js', array(), '20151215', true );
	
	if (is_user_logged_in()  ) {
		// wp_enqueue_script( 'mystartertheme-admin', get_template_directory_uri() . '/dist/js/admin.js', array(), '20151215', true );
		wp_enqueue_style( 'mystartertheme-admin-style', get_template_directory_uri() . '/dist/css/admin.css' );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mystartertheme_scripts' );





/**
 ** Custom Tweaks *  
 **/

//   No CSS for Customers!
function mytheme_customize_register( $wp_customize )
{
   $wp_customize->remove_section('custom_css');
}
add_action( 'customize_register', 'mytheme_customize_register' );


// Page Slug Body Class
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
	$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
	}
add_filter( 'body_class', 'add_slug_body_class' );

//  Disable Quick draft
//  The Quick Draft Dashboard Widget
add_action( 'wp_dashboard_setup', 'remove_draft_widget', 999 );

function remove_draft_widget(){
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}

/**
 ** Security *  
 **/


// remove Autor Kommentar-Link (sort of spam protection)
function rv_remove_comment_author_link( $return, $author, $comment_ID ) { 
	return $author; 
} 
add_filter('get_comment_author_url', 'rv_remove_comment_author_url'); 

function rv_remove_comment_author_url() { 
	return false; 
}
add_filter( 'get_comment_author_link', 'rv_remove_comment_author_link', 10, 3 ); 
