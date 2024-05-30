<?php

/**
 * Theme setup.
 */

namespace App;

use function Roots\bundle;

/**
 * Global definitions
 */
define( 'THEMEDOMAIN', 'san-carlo-theme' );
define( 'THEME_PATH', get_stylesheet_directory_uri() );
define( 'PUBLIC_PATH', THEME_PATH .'/public/' );

include_once ABSPATH . 'wp-admin/includes/plugin.php';

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    bundle('app')->enqueue()->localize('AppData', [
        'site_url' => get_site_url(),
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce('invia_form_rimborso'),
        'booking_nonce' => wp_create_nonce('invia_form_prenotazione'),
        'upload_nonce' => wp_create_nonce('upload_file'),
        'events_en' => do_shortcode( '[events_en]' ),
    ]);

    if (!is_front_page()) { 
        wp_enqueue_script('vuejs', get_stylesheet_directory_uri() . '/app/Theme/vue-2.6.11.min.js', [], '2.6.11');
    }
}, 100);

/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    bundle('editor')->enqueue();
}, 100);


/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {


    $lang = get_stylesheet_directory() . '/resources/lang';
    load_theme_textdomain( 'san-carlo-theme', $lang );

    /**
     * Enable features from the Soil plugin if activated.
     *
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil', [
        'clean-up',
        'nav-walker',
        'nice-search',
        'relative-urls',
    ]);

    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'top_navigation' => __('Top Navigation', THEMEDOMAIN),
        'primary_navigation' => __('Primary Navigation', THEMEDOMAIN),
        'primary_mobile' => __('Primary Mobile', THEMEDOMAIN),
        'amministrazione' => __('Menu Amministrazione Trasparente', THEMEDOMAIN),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');

        /**
     * Remove block widgets
     */
    remove_theme_support( 'widgets-block-editor' );

    /**
     * Remove Gutenberg
     */
    // disable Gutenberg for posts
    add_filter('use_block_editor_for_post', '__return_false', 10);

    // disable Gutenberg for post types
    add_filter('use_block_editor_for_post_type', '__return_false', 10);

    // disable archive title prefix
    add_filter('get_the_archive_title_prefix','__return_false');

    /**
     * Register new role for press agents
     */
    $user_role = get_role( 'subscriber' );
    add_role( 'press', 'Giornalista', $user_role->capabilities );

}, 20);

/**
 * Restricted content
 * redirect to login page if not allowed
 * 
 * Redirect wp-login.php to Accedi Template page
 * 
 * @return void
 */
add_action('template_redirect', function () {

    $query_page = new \WP_Query([
        'post_type' => 'page',
        'post_title' => 'Accedi',
    ]);
    $redirect_page = $query_page->posts[0];
    $restricted_role = get_field('accesso');
    $current_user = get_userdata( get_current_user_id() );

    // Admins can enter
	if (current_user_can( 'edit_posts' ))
        return;

    if (empty($restricted_role))
        return;

    if (is_user_logged_in() && empty(array_diff($current_user->roles, $restricted_role)))
        return;

    wp_redirect(get_permalink($redirect_page->ID));
    exit;

}, 30);


/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', THEMEDOMAIN),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer Sponsor', THEMEDOMAIN),
        'id' => 'sidebar-footer-sponsor',
    ] + $config);

    register_sidebar([
        'name' => __('Footer Menu', THEMEDOMAIN),
        'id' => 'sidebar-footer-menu',
    ] + $config);

    register_sidebar([
        'name' => __('Footer Info', THEMEDOMAIN),
        'id' => 'sidebar-footer-info',
    ] + $config);

    register_sidebar([
        'name' => __('Footer Bottom', THEMEDOMAIN),
        'id' => 'sidebar-footer-bottom',
    ] + $config);

    register_widget( 'bf_buttons_widget' );
});

/**
 * Add data & image to open graph
 */
add_action('wp_head', function() {
    global $post;

    if (is_post_type_archive( 'spettacoli' )) { 
        $img_src = get_stylesheet_directory_uri() . '/seoimg/inscena.jpg';
        echo '<meta property="og:image" content="'.$img_src.'"/>';
        echo '<meta property="og:image:width" content="1200"/>';
        echo '<meta property="og:image:height" content="630"/>';
    }
    else if(get_post_type() == 'spettacoli') {

        if(has_post_thumbnail($post->ID)) {
            $img_src = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'large')[0];
        } else {
            $img_src = get_stylesheet_directory_uri() . '/img/opengraph_image.jpg';
        }

        $excerpt = get_bloginfo('description');

        if($post->post_excerpt != '') {
            $excerpt = strip_tags($post->post_excerpt);
            $excerpt = str_replace("", "'", $excerpt);
        }

        ?>

    <meta property="og:title" content="<?php echo the_title(); ?>"/>
    <meta property="og:description" content="<?= $excerpt; ?>"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="<?php echo the_permalink(); ?>"/>
    <meta property="og:site_name" content="<?php echo get_bloginfo(); ?>"/>
    <meta property="og:image" content="<?php echo $img_src; ?>"/>

<?php
    } else {
        return;
    }
}, 5);