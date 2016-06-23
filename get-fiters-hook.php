<?php

/**
* Get all hooks of filter/action
*/

function print_filters_for( $hook = '' ) {
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
