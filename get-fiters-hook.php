<?php

/*
Plugin Name: Get hook info
Plugin URI: https://github.com/shrimp2t/Lib/blob/master/get-hook-info.php
Description: Show hook info at the bottom of source code.
Author: Matt Shrimp2t
Version: 1.6
Author URI: https://github.com/shrimp2t
*/


/**
 * Get all hooks of filter/action
 */
function s_print_filters_for( $hook = '' ) {
    global $wp_filter;
    if( empty( $hook ) || !isset( $wp_filter[$hook] ) ) return;
    $ret='';
    foreach($wp_filter[$hook] as $priority => $realhook){
        foreach($realhook as $hook_k => $hook_v){
            $hook_echo=(is_array($hook_v['function'])?get_class($hook_v['function'][0]).':'.$hook_v['function'][1]:$hook_v['function']);
            $ret.=  "\n$priority $hook_echo";
        }
    }
    return $ret;
}

function s_footer_echo_hooks(){
    $hook = isset( $_GET['hook'] ) ? $_GET['hook'] : '';
    if ( ! $hook ) {
        return ;
    }
    echo "\r\n<!-- HOOK-INFO -->\r\n";

    echo "\r\n<!--\r\n";
    echo s_print_filters_for( $hook );
    echo "\r\n-->\r\n";
}

add_action( 'wp_footer', 's_footer_echo_hooks', 9999 );
