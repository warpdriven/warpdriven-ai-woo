<?php
/*
Plugin Name: WarpDriven GPT Copywriting
Plugin URI: https://warp-driven.com/service/warpdriven-gpt-copywriting/
Description: WarpDriven GPT Copywriting
Author: Warp Driven Technology
Author URI: https://warp-driven.com/
Text Domain: warpdriven-gpt-copywriting
Domain Path: /languages/
Version: 1.2.3
*/
include_once __DIR__ . '/src/WGCCore.php';

use WarpDrivenWGCCore\WGCCore;

if (!defined('WD_WGC_WOO_CORE_CORE_FILE')) {
    define('WD_WGC_WOO_CORE_CORE_FILE', __FILE__);
}

if (!function_exists('wd_wgc_woo_activation')) {
    function wd_wgc_woo_activation()
    {

    }
}

register_activation_hook(__FILE__, 'wd_wgc_woo_activation');

if (!function_exists('wd_wgc_woo_deactivation')) {
    function wd_wgc_woo_deactivation()
    {
        delete_option('wgc_api_key');
    }
}

register_deactivation_hook(__FILE__, 'wd_wgc_woo_deactivation');

add_action('init', function () {
    load_plugin_textdomain('wd_wgc_woo', false, dirname(__FILE__) . '/languages');
    if (!defined('WD_WGCCore_Loaded')) {
        $core = new WGCCore();
    }
});

add_action('admin_enqueue_scripts', function () {
    $plugin_version = get_plugin_data(WD_WGC_WOO_CORE_CORE_FILE)['Version'];
    // wp_enqueue_script('wd_wgc_woo-script', plugins_url('/assets/js/backend.js', WD_WGC_WOO_CORE_CORE_FILE), array('jquery'), $plugin_version, true);
    // wp_enqueue_style('wd_wgc_woo-style', plugins_url('/assets/css/backend.css', WD_WGC_WOO_CORE_CORE_FILE), array(), $plugin_version);
    wp_enqueue_script('wd_wgc_woo-script', plugins_url('/assets/dist/main.js', WD_WGC_WOO_CORE_CORE_FILE), array('jquery'), $plugin_version, true);
    wp_enqueue_style('wd_wgc_woo-style', plugins_url('/assets/dist/main.css', WD_WGC_WOO_CORE_CORE_FILE), array(), $plugin_version);
    wp_enqueue_style('wd_wgc_woo-style-vue', plugins_url('/assets/dist/css/vue-styles.css', WD_WGC_WOO_CORE_CORE_FILE), array(), $plugin_version);
    
    wp_style_add_data('wd_wgc_woo-style', 'rtl', 'replace');
    wp_localize_script('wd_wgc_woo-script', 'wd_wgc_woo_', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
});

add_action('wp_enqueue_scripts', function () {
    $plugin_version = get_plugin_data(WD_WGC_WOO_CORE_CORE_FILE)['Version'];
    // wp_enqueue_script('wd_wgc_woo-frontend-script', plugins_url('/assets/js/frontend.js', WD_WGC_WOO_CORE_CORE_FILE), array('jquery'), $plugin_version, true);
    // wp_enqueue_style('wd_wgc_woo-frontend-style', plugins_url('/assets/css/frontend.css', WD_WGC_WOO_CORE_CORE_FILE), array(), $plugin_version);
    wp_enqueue_script('wd_wgc_woo-script', plugins_url('/assets/dist/main.js', WD_WGC_WOO_CORE_CORE_FILE), array('jquery'), $plugin_version, true);
    wp_enqueue_style('wd_wgc_woo-style', plugins_url('/assets/dist/main.css', WD_WGC_WOO_CORE_CORE_FILE), array(), $plugin_version);
    wp_enqueue_style('wd_wgc_woo-style-vue', plugins_url('/assets/dist/css/vue-styles.css', WD_WGC_WOO_CORE_CORE_FILE), array(), $plugin_version);
    
    wp_style_add_data('wd_wgc_woo-frontend-style', 'rtl', 'replace');
    wp_localize_script('wd_wgc_woo-frontend-script', 'wd_wgc_woo_', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
});