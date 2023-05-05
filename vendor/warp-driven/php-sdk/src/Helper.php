<?php

namespace WarpDriven\PhpSdk;

class Helper
{

    // public static $WARP_AI_HOST = "https://ai-stg.warp-driven.com/";

    // public static $WARP_NLP_HOST = "https://nlp-stg.warp-driven.com/";

    // public static $WARP_DATA_HOST = "https://data-stg.warp-driven.com/";

    // public static $WARP_API_HOST = "https://api-stg.warp-driven.com/";


    public static $WARP_AI_HOST = "https://ai.warp-driven.com/";

    public static $WARP_NLP_HOST = "https://nlp.warp-driven.com/";

    public static $WARP_DATA_HOST = "https://data.warp-driven.com/";

    public static $WARP_API_HOST = "https://api.warp-driven.com/";

    /**
     * Query similar products according to product ID
     *
     * $search_url       address
     * $api_key          authorization key
     * $website_code    Visual Search Website identity
     * $product_id      product ID
     *
     * return
     * [
     *      {
     *          "product_id": 956,
     *          "distance": 0.11267366409301759,
     *          "recall_type": "1"
     *      },
     *      ... ...
     * ]
     */
    public static function visual_search($api_key, $product_id)
    {
        $search_url =  self::$WARP_AI_HOST.'latest/vs/internal_search';
        $search_url .= '?' . http_build_query(array(
                    'shop_variant_id' => $product_id
                )
            );
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key),"timeout"=>1200));
        if (!is_wp_error($response)) {
            $result = json_decode($response['body']);
            if(!is_array($result)){
                return array(); 
            };
            return $result;
        }else{
            return array();
        }
    }

    /**
     * Image Processing History
     * 
     * $api_key          authorization key
     * $page_no         page number
     * $page_size       page Size
     * 
     */
    public static function handle_history($api_key, $page_no,$page_size)
    {
        $search_url = self::$WARP_DATA_HOST.'latest/product/handle_history';
        $search_url .= '?' . http_build_query(array(
                    'page_no' => $page_no,
                    'page_size' => $page_size
                )
            );
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key),"timeout"=>1200));
        return self::response($response);
    }


        /**
     * Initialize product image
     * 
     * $api_key          authorization key
     * $args            init args
     */
    public static function init_products($api_key, $args)
    {
        $search_url = self::$WARP_DATA_HOST.'latest/product/upsert/';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }


    /**
     * delete product
     * $api_key          authorization key
     * $args {
     *      "delete_shop_variant_ids": [
     *           "001", "002"
     *       ]
     *   }
     */
    public static function delete_product($api_key, $args)
    {
        $search_url = self::$WARP_DATA_HOST.'latest/product/delete/';
        $response = wp_remote_request($search_url,array("method"=>"DELETE","headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

    /**
     * Get initialization status
     * $api_key          authorization key
     */
    public static function get_vs_credit_status($api_key)
    {
        $search_url = self::$WARP_AI_HOST.'latest/vs/get_vs_credit_status?plan_id=1';
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key),"timeout"=>1200));
        return self::response($response);
    }

    /**
     * gpt
     * $api_key        authorization key
     */
    public static function gpt($api_key,$args)
    {
        $search_url = self::$WARP_NLP_HOST.'latest/writer/gpt';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }


    public static function assistant($api_key,$args)
    {
        $search_url = self::$WARP_NLP_HOST.'latest/writer/assistant';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

    /**
     * 2023 04 21
     */
    public static function get_all_task_info($api_key)
    {
        $search_url = self::$WARP_NLP_HOST.'latest/writer/all_task_info';
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"timeout"=>1200));
        return self::response_by_get($response);
    }

    public static function get_active_task_info($api_key)
    {
        $search_url = self::$WARP_NLP_HOST.'latest/writer/active_task_info';
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"timeout"=>1200));
        return self::response_by_get($response);
    }

    public static function get_task($api_key,$args)
    {
        $search_url = self::$WARP_NLP_HOST.'latest/writer/task?task_id='.$args;
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"timeout"=>1200));
        return self::response_by_get($response);
    }

    public static function get_tasks($api_key)
    {
        $search_url = self::$WARP_NLP_HOST.'latest/writer/history?top=20';
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"timeout"=>1200));
        return self::response_by_get($response);
    }

     /**
     * 2023 04 18
     */
    public static function get_task_status($api_key,$args)
    {
        $search_url = self::$WARP_NLP_HOST.'latest/writer/task_status';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

    /**
     * erp_user_create
     * $args         Create Erp User
     */
    public static function erp_user_create($args)
    {
        $search_url = self::$WARP_API_HOST.'erp_user/create';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }


    /**
     * get_user_exsited
     * $email         email
     */
    public static function get_user_exsited($email)
    {
        $search_url = self::$WARP_API_HOST.'erp_user?erp_user_email='.$email;
        $response = wp_remote_get($search_url,array("timeout"=>1200));
        return self::response($response);
    }

    /**
     * create_erp_user
     * $arg           Erp User
     */
    public static function create_erp_user($args)
    {
        $search_url = self::$WARP_API_HOST.'erp_user/create';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

    /**
     * create_erp_user
     * $arg           Erp User
     */
    public static function my_website($args)
    {
        $search_url = self::$WARP_API_HOST.'my_website';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>1200));
        return self::response($response);
    }

     /**
     * create_my_website
     * $arg           Erp User
     */
    public static function create_my_website($args)
    {
        $search_url = self::$WARP_API_HOST.'my_website/create';
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
            return $response['response'];
        }else{
            return $response;
        }
    }
}