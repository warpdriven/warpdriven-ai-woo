<?php
/**
 * WooCommerce ajax
 */

namespace WarpDriven\WgcCore;

use WarpDriven\PhpSdk\Helper;

class WGCAjax
{


    public function __construct()
    {
        $this->add_ajax();
    }


    /**
     * Add wordpress ajax
     */
    function add_ajax()
    {
        $ajax_events = array(
            'get_woo_product_categories',
            'query_product_page',
            'product_description',
            'article',
            'translate'
        );
        foreach ($ajax_events as $ajax_event) {
            add_action('wp_ajax_wgc_' . $ajax_event, array($this, $ajax_event));
            add_action('wp_ajax_nopriv_wgc_' . $ajax_event, array($this, $ajax_event));
        }
    }



    /**
     * Add Query Product Page 
     */
    public function query_product_page(){

        $limit = $_POST["limit"];
        $page = $_POST["page"];
        $name = $_POST["name"];
        $sku = $_POST["sku"];
        $product_slug = $_POST["product_slug"];

        $tax_query = array();

        if($product_slug){
            array_push($tax_query,
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => array($product_slug)
                )
            );
        }
   
        $result = wc_get_products(
            array(
                'paginate'=>true,
                'limit' => $limit?$limit:80,
                'page' => $page?$page:1,
                'name' => $name?$name:'',
                'sku' => $sku?$sku:'',
                'tax_query' => $tax_query,
                'orderby' => 'date',
                'order' => 'DESC'
            )
        );

        $data = array();

        foreach($result->products as $product){
            array_push($data,array(
                "product_id"=> $product->get_ID(),
                "product_sku"=> $product->sku,
                "product_title"=> $product->name,
                "product_image_html"=>$product->get_image(),
                "product_description"=>$product->get_short_description()
            ));
        }

        wp_send_json(array(
            "data"=>$data,
            "total"=> $result->total,
            "max_num_pages"=> $result->max_num_pages,
            "product_slug"=> $product_slug
        ));
    }

    /**
     * Get the product categorys from woocommerce
     */
    public function get_woo_product_categories()
    {
        $args = array(
            'taxonomy' => 'product_cat',
            'orderby' => 'name',
            'order' => 'asc',
            'count' => true,
            'pad_counts' => true
        );
        $product_categories = array_values(get_terms($args));
        error_log(print_r($product_categories, true));
        wp_send_json($product_categories);
    }

    /**
     * Get the product categorys from woocommerce
     */
    public function product_description(){
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);
        error_log(print_r($data, true));
        wp_send_json(Helper::product_description(WGCCore::getApiKey(), json_encode($data)));
    }

    /**
     * Get the product categorys from woocommerce
     */
    public function article(){
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);
        error_log(print_r($data, true));
        wp_send_json(Helper::article(WGCCore::getApiKey(), json_encode($data)));
    }

    /**
     * Get the product categorys from woocommerce
     */
    public function translate(){
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);
        error_log(print_r($data, true));
        wp_send_json(Helper::translate(WGCCore::getApiKey(), json_encode($data)));
    }


    
} 