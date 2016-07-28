<?php
/*
* Plugin Name: Network Sidebar Widget Fallback
* Author: Russell Fair
* Version: 0.1
* Description: Provides an intuative way of using the same sidebar accross a network of sites. Basically makes the "parent site" the fallback in situations where the "child site" doesn't have a sidebar configured.
*/

require_once('class-network-sidebar-fallback.php');
if(class_exists('Network_Sidebar_Fallback')){
    new Network_Sidebar_Fallback;
}
