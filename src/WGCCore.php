<?php

namespace WarpDriven\WgcCore;
class WGCCore
{
    public function __construct()
    {
        if (defined('WD_WGCCore_Loaded')) {
            return;
        }
        define('WD_WGCCore_Loaded', true);
        $home = new WGCHomePage();
        $setting = new WGCSettingPage();
        $ajax = new WGCAjax();
    }

    public static function getApiKey()
    {
        return get_option('wgc_api_key');
    }

    public static function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }
}