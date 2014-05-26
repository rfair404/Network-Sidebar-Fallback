<?php
/*
* Plugin Name: Network Sidebar Fallback
* Author: Russell Fair
* Version: 0.2
* Description: Uses the parent site's sidebar as the fallback for non-widgetized sidebars
*/

function nsf_network_sidebar($index, $has_widgets){
    if($has_widgets || is_admin()){
        //this only does something when the sidebar is empty
        return;
    }
    echo get_site_transient( 'nsf_' . $index . '_widgets' );
}
add_action('dynamic_sidebar_before', 'nsf_network_sidebar', 10, 2);

function nsf_gather_sidebar_output(){

    if ( ! is_main_site() ){
        //prevent non-child sites from messing this up
        return;
    }

    global $wp_registered_sidebars;
    foreach ($wp_registered_sidebars as $wp_registered_sidebar => $sidebar_args){
        if(!($this_sidebar = get_site_transient( 'nsf_' . $wp_registered_sidebar  . '_widgets' ))){
            cache_sidebar($wp_registered_sidebar);
        }
    }
}
add_action( 'init', 'nsf_gather_sidebar_output');

function cache_sidebar($sidebar_id){
    ob_start();
    if ( ! dynamic_sidebar( $sidebar_id ) ) :
        endif; // end sidebar widget area

    $sidebar_output = ob_get_contents();
    ob_end_clean();
    //saves for one hour
    set_site_transient( 'nsf_' . $sidebar_id . '_widgets', $sidebar_output, 60*60 );
}

/**
 * Enqueue and localize our scripts
 */
add_action( 'admin_enqueue_scripts', 'nsf_enqueue_ajax_scripts' );
function nsf_enqueue_ajax_scripts() {
    global $current_screen;
    $version = (int) '2';

    // Only register these scripts if we're on the widgets page
    if ( $current_screen->id == 'widgets' ) {
        wp_enqueue_script( 'nsf_ajax_scripts', plugins_url( '/js/widget-actions.js' , __FILE__ ), array( 'jquery' ), $version, true );
        wp_localize_script( 'nsf_ajax_scripts', 'nsf_AJAX', array( 'nsf_widget_nonce' => wp_create_nonce( 'widget-update-nonce' ) ) );
    }
}
/**
 * Register our AJAX call
 */
add_action( 'wp_ajax_nsf-reset-transient', 'nsf_clear_transient', 1 );

/**
 * AJAX Helper to delete our transient when a widget is saved
 */
function nsf_clear_transient() {

    // Delete our footer transient.  This runs when a widget is saved or updated.  Only do this if our nonce is passed.
    if ( ! empty( $_REQUEST['nsf-widget-nonce'] ) ){
        delete_site_transient( 'nsf_'.$_REQUEST['sidebar'].'_widgets' );
        cache_sidebar($_REQUEST['sidebar']);
    }
    die();
}
