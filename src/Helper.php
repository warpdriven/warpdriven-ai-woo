<?php

namespace WarpDrivenWGCCore;
include_once "WDEnv.php";
class Helper
{
    private static $WARP_API_HOST;
    private static $WARP_NLP_HOST;

    public static function init()
    {
        if (WDEnv::$env_value == 'prod') {
            self::$WARP_API_HOST = 'https://api.warp-driven.com';
            self::$WARP_NLP_HOST = 'https://nlp.warp-driven.com/latest';
        } else {
            self::$WARP_API_HOST = 'https://api-stg.warp-driven.com';
            self::$WARP_NLP_HOST = 'https://nlp-stg.warp-driven.com/latest';
        }
    }

    
    /**
     * gpt
     * $api_key        authorization key
     * $args           goodsParam
     */
    public static function gpt($api_key,$args)
    {
        $search_url = self::$WARP_NLP_HOST.'/writer/gpt';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

    /**
     *assistant
     *$args     assistant endpoint         
     */
    public static function assistant($api_key,$args)
    {
        $search_url = self::$WARP_NLP_HOST.'/writer/assistant';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

    /**
     * get_all_task_info
     * check the GPT task status
     */
    public static function get_all_task_info($api_key)
    {
        $search_url = self::$WARP_NLP_HOST.'/writer/all_task_info';
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"timeout"=>1200));
        return self::response_by_get($response);
    }

    /**
    * get_active_task_info
    * check the all unfinished tasks' simple info
    */
    public static function get_active_task_info($api_key)
    {
        $search_url = self::$WARP_NLP_HOST.'/writer/active_task_info';
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"timeout"=>1200));
        return self::response_by_get($response);
    }

    /**
    *get_task
    *$args  task_id
    *Get task specified GPT Copywriting result
    */
    public static function get_task($api_key,$args)
    {
        $search_url = self::$WARP_NLP_HOST.'/writer/task?task_id='.$args;
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"timeout"=>1200));
        return self::response_by_get($response);
    }

    /**
     * get_tasks
     * Get the website historical data
     */
    public static function get_tasks($api_key)
    {
        $search_url = self::$WARP_NLP_HOST.'/writer/history?top=20';
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"timeout"=>1200));
        return self::response_by_get($response);
    }

    /**
     * get_task_status
     * check the GPT task status
     */
    public static function get_task_status($api_key,$args)
    {
        $search_url = self::$WARP_NLP_HOST.'/writer/task_status';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

    /**
     * erp_user_create
     * $args         Create Erp User
     */
    public static function erp_user_create($args)
    {
        $search_url = self::$WARP_API_HOST.'/erp_user/create';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }


    /**
     * get_user_exsited
     * $email         email
     */
    public static function get_user_exsited($email)
    {
        $search_url = self::$WARP_API_HOST.'/erp_user?erp_user_email='.$email;
        $response = wp_remote_get($search_url,array("timeout"=>1200));
        return self::response($response);
    }

    /**
     * create_erp_user
     * $arg           Erp User
     */
    public static function create_erp_user($args)
    {
        $search_url = self::$WARP_API_HOST.'/erp_user/create';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

    /**
     * create_erp_user
     * $arg           Erp User
     */
    public static function my_website($args)
    {
        $search_url = self::$WARP_API_HOST.'/my_website';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

     /**
     * create_my_website
     * $arg           Erp User
     */
    public static function create_my_website($args)
    {
        $search_url = self::$WARP_API_HOST.'/my_website/create';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

    
    
    /**
     * Standard return results
     */
    public static function response($response,$args='{}'){
        error_log(print_r($response, true));
        if (!is_wp_error($response)) {
            $result = json_decode($response['body']);
            $result->code = $response['response']?$response['response']['code']:200;
            if($result->detail){
                $result->status = false;
                $result->msg = $result->detail;
            }
            return $result;
        }else{
            return $response;
        }
    }

    /**
     * Standard return results
     */
    public static function response_by_get($response,$args='{}'){
        if (!is_wp_error($response)) {
            $result = json_decode($response['body']);
            if(!$result) return $response;
            $result->code = $response['response']?$response['response']['code']:200;
            if($result->detail){
                $result->status = false;
                $result->msg = $result->detail;
            }
            return $result;
        }else{
            return $response;
        }
    }
}
