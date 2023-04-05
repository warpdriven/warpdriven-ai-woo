<?php

namespace WarpDriven\PhpSdk;

class Helper
{
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
        $search_url = 'https://ai-stg.warp-driven.com/latest/vs/internal_search';
        $search_url .= '?' . http_build_query(array(
                    'shop_variant_id' => $product_id
                )
            );
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key),"timeout"=>300));
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
        $search_url = 'https://data-stg.warp-driven.com/latest/product/handle_history';
        $search_url .= '?' . http_build_query(array(
                    'page_no' => $page_no,
                    'page_size' => $page_size
                )
            );
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key),"timeout"=>300));
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
        $search_url = 'https://data-stg.warp-driven.com/latest/product/init';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>300));
        return self::response($response);
    }


    /**
     * Get initialization status
     * $api_key          authorization key
     */
    public static function get_vs_credit_status($api_key)
    {
        $search_url = 'https://data-stg.warp-driven.com/latest/product/get_vs_credit_status?plan_id=1';
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key),"timeout"=>300));
        return self::response($response);
    }

    /**
     * gpt
     * $api_key        authorization key
     */
    public static function gpt($api_key,$args)
    {
        $search_url = 'https://nlp-stg.warp-driven.com/latest/writer/gpt';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>300));
        return self::response($response);
    }

    /**
     * erp_user_create
     * $args         Create Erp User
     */
    public static function erp_user_create($args)
    {
        $search_url = 'https://api-stg.warp-driven.com/erp_user/create';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>300));
        return self::response($response);
    }


    /**
     * get_user_exsited
     * $email         email
     */
    public static function get_user_exsited($email)
    {
        $search_url = 'https://api-stg.warp-driven.com/erp_user?erp_user_email='.$email;
        $response = wp_remote_get($search_url,array());
        return self::response($response);
    }

    /**
     * create_erp_user
     * $arg           Erp User
     */
    public static function create_erp_user($args)
    {
        $search_url = 'https://api-stg.warp-driven.com/erp_user/create';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>300));
        return self::response($response);
    }

    /**
     * create_erp_user
     * $arg           Erp User
     */
    public static function my_website($args)
    {
        $search_url = 'https://api-stg.warp-driven.com/my_website';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>300));
        return self::response($response);
    }

     /**
     * create_my_website
     * $arg           Erp User
     */
    public static function create_my_website($args)
    {
        $search_url = 'https://api-stg.warp-driven.com/my_website/create';
        $response = wp_remote_post($search_url,array("headers"=>array("Content-Type"=>"application/json"),"body"=>$args,"timeout"=>300));
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
}