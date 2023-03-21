<?php

namespace WarpDriven\PhpSdk;

class Helper
{
    /**
     * Query similar products according to product ID
     *
     * $search_url      Visual Search Search engine address
     * $api_key         Visual Search Search engine authorization key
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
                    'shop_sku_id' => $product_id
                )
            );
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key),"timeout"=>300));
        if (!is_wp_error($response)) {
            $result = json_decode($response['body']);
            return $result;
        }else{
            return array();
        }
    }

    /**
     * Image Processing History
     * 
     * $api_key         Visual Search Search engine authorization key
     * $page_no         page number
     * $page_size       page Size
     * 
     */
    public static function handle_history($api_key, $page_no,$page_size)
    {
        $search_url = 'https://data.warp-driven.com/latest/product/handle_history';
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
     * $api_key         Visual Search Search engine authorization key
     * $args            init args
     */
    public static function init_products($api_key, $args)
    {
        $search_url = 'https://data.warp-driven.com/latest/product/init';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>300));
        return self::response($response);
    }


    /**
     * Get initialization status
     * $api_key         Visual Search Search engine authorization key
     */
    public static function get_vs_credit_status($api_key)
    {
        $search_url = 'https://data.warp-driven.com/latest/product/get_vs_credit_status';
        $response = wp_remote_get($search_url,array("headers"=>array("X-API-Key"=>$api_key),"timeout"=>300));
        return self::response($response);
    }

    /**
     * product_description
     * $api_key         Visual Search Search engine authorization key
     */
    public static function product_description($api_key,$args)
    {
        $search_url = 'https://nlp-stg.warp-driven.com/latest/writer/product_description';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>300));
        return self::response($response);
    }

    /**
     * article
     * $api_key         Visual Search Search engine authorization key
     */
    public static function article($api_key,$args)
    {
        $search_url = 'https://nlp-stg.warp-driven.com/latest/writer/article';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>300));
        return self::response($response);
    }

     /**
     * translate
     * $api_key         Visual Search Search engine authorization key
     */
    public static function translate($api_key,$args)
    {
        $search_url = 'https://nlp-stg.warp-driven.com/latest/writer/translate';
        $response = wp_remote_post($search_url,array("headers"=>array("X-API-Key"=>$api_key,"Content-Type"=>"application/json"),"body"=>$args,"timeout"=>300));
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