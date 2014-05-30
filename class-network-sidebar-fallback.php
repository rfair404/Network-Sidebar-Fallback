<?php

class Network_Sidebar_Fallback{
    public $sidebars, $cache_time;

    function __construct(){
        add_action('init', array($this, 'set_sidebars'), 10);
        add_action('init', array($this, 'set_cache_timeout'), 10);

        add_action('dynamic_sidebar_before', array($this, 'network_sidebar'), 10, 2);
        add_action( 'init', array($this, 'collect_sidebars'), 10);
        add_action( 'init', array($this, 'refresh_sidebars'), 10);
        add_filter('widget_update_callback', array($this, 'widget_change'), 10, 4);
    }

    /**
    * gets the registered sidebars and sets $this->sidebars
    */
    function set_sidebars(){
        global $wp_registered_sidebars;
        $this->sidebars = $wp_registered_sidebars;
    }


    /**
    * gets the registered sidebars and sets $this->sidebars
    * provides access to shorten or lengthen the cache time
    */
    function set_cache_timeout(){
        $this->cache_time = apply_filters('nsf_cache_time', 60*60);
    }

    /**
    * Displays the network wide sidebar when existing widgets aren't there
    */
    function network_sidebar($index, $has_widgets){
        if($has_widgets || is_admin() || is_main_site() ){
            //this only does something when the child site sidebar is empty
            return;
        }
        echo get_site_transient( 'nsf_' . $index . '_widgets' );
    }

    /**
    * caches the sidebars if the transient doesn't exist (reload)
    */
    function collect_sidebars(){
        if ( ! is_main_site() ){
            //prevent non-child sites from messing this up
            return;
        }

        foreach ($this->sidebars as $sidebar => $sidebar_args){
            if(!($this_sidebar = get_site_transient( 'nsf_' . $sidebar  . '_widgets' ))){
                $this->cache_sidebar($sidebar);
            }
        }
    }


    /**
    * refreshes all sidebars
    */
    function refresh_sidebars(){
        if ( ! is_main_site() ){
            return;
        }

        if ( get_site_option('nsf_refresh_required') != true ){
            return;
        }

        foreach ($this->sidebars as $sidebar => $sidebar_args){
            $this->cache_sidebar($sidebar);
        }
    }

    /**
    * caches a single sidebar
    * @param string $sidebar_id the id (slug) of the sidebar to update
    */
    function cache_sidebar($sidebar_id){
        ob_start();
        dynamic_sidebar( $sidebar_id );

        $sidebar_output = ob_get_contents();
        ob_end_clean();
        //saves for one hour
        set_site_transient( 'nsf_' . $sidebar_id . '_widgets', $sidebar_output, $this->cache_time );
    }

    /**
    * attaches to the widget save option and triggers the cache refresh
    * @return array $new the new widget instance
    */
    function widget_change($instance, $new, $old, $this_widget){
        if ( is_main_site() ){
            $this->trigger_refresh();
        }
        return $new;
    }

    /**
    * forces wp to do a sidebar cache reload on next init
    */
    function trigger_refresh(){
        update_site_option('nsf_refresh_required', true);
    }
}
