<?php

/*
Plugin Name: Get hook info
Plugin URI: https://github.com/shrimp2t/Lib/blob/master/get-hook-info.php
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
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
    echo "\r\n<!--\r\n";
    echo s_print_filters_for();
    echo "\r\n-->\r\n";
}

add_action( 'wp_footer', 'footer_echo_hooks', 99 );
