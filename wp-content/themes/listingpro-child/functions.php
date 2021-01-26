<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'bootstrap','Magnific-Popup','popup-component','Font-awesome','Mmenu','MapBox','Chosen','bootstrap-datetimepicker-css','Slick-css','Slick-theme','css-prettyphoto','icon8','Color','custom-font','Main','Responsive','dynamiclocation','lp-body-overlay','bootstrapslider','mourisjs' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

remove_action( 'init', 'jplug_admin_init' );


add_action( 'init', 'jplug_admin_init_child' );
function jplug_admin_init_child() {
    if ( class_exists('WPBakeryVisualComposerAbstract') ) {
        vc_disable_frontend();

        require WP_PLUGIN_DIR.'/listingpro-plugin/inc/vc_mods/vc_mods.php';
        $vc_template_dir =  WP_PLUGIN_DIR.'/listingpro-plugin/inc/vc_mods/vc_templates';
        vc_set_shortcodes_templates_dir( $vc_template_dir );
        include_once(WP_PLUGIN_DIR.'/listingpro-plugin/vc_special_elements.php');
        include_once(WP_PLUGIN_DIR.'/listingpro-plugin/vc-icon-param.php');
        $check = get_option( 'theme_activation' );
        if(!empty($check) && $check != 'none'){
            include_once(WP_PLUGIN_DIR.'/listingpro-plugin/shortcodes/pricing.php');
            include_once(WP_PLUGIN_DIR.'/listingpro-plugin/shortcodes/submit.php');
            include_once(WP_PLUGIN_DIR.'/listingpro-plugin/shortcodes/edit.php');
            include_once(WP_PLUGIN_DIR.'/listingpro-plugin/shortcodes/checkout.php');
        }

        include_once(WP_PLUGIN_DIR.'/listingpro-plugin/shortcodes/category-element.php');
    }
}

// END ENQUEUE PARENT ACTION
