<?php

/**
 * WooCommerce ajax
 */

namespace WarpDrivenWGCCore;

include_once "Helper.php";

use WarpDrivenWGCCore\Helper;

use WC_Product;

class WGCAjax
{


    public function __construct()
    {
        Helper::init();
        $this->add_ajax();
    }


    /**
     * Add wordpress ajax
     */
    function add_ajax()
    {
        $ajax_events = array(
            'get_product',
            'get_woo_product_categories',
            'query_product_page',
            'gpt',
            'assistant',
            'save_product',
            'save_post',
            'get_user_exsited',
            'create_erp_user',
            'create_my_website',
            'my_website',
            // 
            "get_task_status",
            "get_task",
            "get_tasks",
            'get_all_task_info',
            'get_active_task_info',
        );
        foreach ($ajax_events as $ajax_event) {
            add_action('wp_ajax_wgc_' . $ajax_event, array($this, $ajax_event));
            add_action('wp_ajax_nopriv_wgc_' . $ajax_event, array($this, $ajax_event));
        }
    }

    /**
     * Get product by id 
     */
    public function get_product()
    {
        $id = (int)$_GET["id"];
        $product = wc_get_product(intval($id));
        $tags = $product->get_tag_ids();
        $terms = array();

            if ($tags) {
                foreach ($tags as $tag) {
                    array_push($terms, get_term_by('id', $tag, 'product_tag')->name);
                }
            }

        wp_send_json(array(
        "product_id" => $product->get_ID(),
        "product_sku" => $product->sku,
        "product_title" => $product->name,
        "product_image_html" => $product->get_image(),
        'main_image_url' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
        "keywords" =>  $terms,
        "product_description" => $product->get_description(),
        "product_short_description" => $product->get_short_description()
        ));
    }



    /**
     * Add Query Product Page 
     */
    public function query_product_page()
    {

        $limit = (int)$_POST["limit"];
        $page = (int)$_POST["page"];
        $name = sanitize_text_field($_POST["name"]);
        $sku = sanitize_text_field($_POST["sku"]);
        $product_slug = sanitize_text_field($_POST["product_slug"]);

        $tax_query = array();

        if ($product_slug) {
            array_push(
                $tax_query,
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => array($product_slug)
                )
            );
        }

        $result = wc_get_products(
            array(
                'paginate' => true,
                'limit' => $limit ? $limit : 80,
                'page' => $page ? $page : 1,
                'name__like' => $name ? $name : '',
                'sku' => $sku ? $sku : '',
                'tax_query' => $tax_query,
                'orderby' => 'date',
                'order' => 'DESC'
            )
        );

        $data = array();

        foreach ($result->products as $product) {

            $tags = $product->get_tag_ids();

            $terms = array();

            if ($tags) {
                foreach ($tags as $tag) {
                    array_push($terms, get_term_by('id', $tag, 'product_tag')->name);
                }
            }

            array_push($data, array(
                "product_id" => $product->get_ID(),
                "product_sku" => $product->sku,
                "product_title" => $product->name,
                "product_image_html" => $product->get_image(),
                'main_image_url' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
                "keywords" =>  $terms,
                "product_description" => $product->get_description(),
                "product_short_description" => $product->get_short_description()
            ));
        }

        wp_send_json(array(
            "data" => $data,
            "total" => $result->total,
            "max_num_pages" => $result->max_num_pages,
            "product_slug" => $product_slug
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
        foreach($product_categories as $key=>$value){
            $product_categories[$key]->name=str_replace("&amp;","&",$value->name);
        }
        wp_send_json($product_categories);
    }

    /**
     * 添加新商品
     */
    public function save_product()
    {
        // 获取 POST 请求中提交的商品信息
        $request_body = file_get_contents('php://input');
        $product_data = json_decode($request_body);

        // 创建新的商品对象
        if ($product_data->product_id) {
            $new_product = wc_get_product($product_data->product_id);
        } else {
            $new_product = new WC_Product();
        }


        // 设置商品信息
        $new_product->set_name($product_data->product_name);
        $new_product->set_description($product_data->product_description);
        $new_product->set_short_description($product_data->product_short_description);

        // 保存商品对象
        $new_product->save();

        if ($new_product->get_id()) {
            wp_set_object_terms($new_product->get_id(), $product_data->product_keywords, 'product_tag');
        }

        // 保存商品对象
        wp_send_json(array(
            'status' => true,
            'msg' => $new_product->get_id() ? 'Product edit successfully.' : 'New product added successfully.',
            'product_id' => $new_product->get_id(),
        ));
    }

    /**
     * 添加新商品
     */
    public function save_post()
    {
        // 获取 POST 请求中提交的商品信息
        $request_body = file_get_contents('php://input');
        $post_data = json_decode($request_body);
        $user_id = get_current_user_id();

        $post_title = $post_data->title;
        $post_content = $post_data->content;
        $post_tags = $post_data->keywords;

        $post_id = wp_insert_post(array(
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_author' => $user_id,
            'post_type' => 'post',
            'post_status' => 'publish'
        ));

        if ($post_id) {
            wp_set_object_terms($post_id, $post_tags, 'post_tag');
        }

        // 保存商品对象
        wp_send_json(array(
            'status' => true,
            'msg' => $post_id ? 'Product post successfully' : 'New post added successfully.',
            'post_id' => $post_id,
        ));
    }


    public function gpt()
    {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);
        $result = Helper::gpt(WGCCore::getApiKey(), json_encode($data));
        wp_send_json($result, $result->code);
    }

    public function assistant()
    {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);
        $result = Helper::assistant(WGCCore::getApiKey(), json_encode($data));
        wp_send_json($result, $result->code);
    }

    /**
     * 2023 04 18
     */
    public function get_task_status()
    {
        $data = sanitize_text_field($_GET["task_id"]);
        $result = Helper::get_task_status(WGCCore::getApiKey(), $data);
        wp_send_json($result, $result->code);
    }
    public function get_task()
    {
        $data = sanitize_text_field($_GET["task_id"]);
        $result = Helper::get_task(WGCCore::getApiKey(), $data);
        wp_send_json($result, $result->code);
    }
    public function get_tasks()
    {
        $result = Helper::get_tasks(WGCCore::getApiKey());
        wp_send_json($result, $result->code);
    }
    public function get_all_task_info()
    {
        $result = Helper::get_all_task_info(WGCCore::getApiKey());
        wp_send_json($result, $result->code);
    }
    public function get_active_task_info()
    {
        $result = Helper::get_active_task_info(WGCCore::getApiKey());
        wp_send_json($result, $result->code);
    }

    public function get_user_exsited()
    {

        $result = Helper::get_user_exsited(get_option('admin_email'));

        if ($result->data) {
            // 如果用户密码已存在
            if (get_option("wgc_erp_user_password")) {
                $ru = $this->create_website();
                if (!$ru->data) {
                    $result = array("status" => true, "msg" => "reset post!", "data" => false, "flag" => 1);
                }
            } else {
                $result = array("status" => true, "msg" => "reset post!", "data" => false, "flag" => 1);
            }
        }

        wp_send_json($result, $result->code);
    }

    public function create_erp_user()
    {
        $request_body = file_get_contents('php://input');

        $data = json_decode($request_body);
        $data->email = get_option('admin_email');

        $shop_url = home_url();

        $host = parse_url($shop_url, PHP_URL_HOST);
        $shop_code = explode(".", $host)[0];
        $shop_url = str_replace('http://', '', $shop_url);
        $shop_url = str_replace('https://', '', $shop_url);
        $data->platform_type = 1; // 这个属性不是从 WooCommerce 配置中获取的

        $result = Helper::get_user_exsited($data->email);

        if ($result->status) {

            if ($result->data) {
            } else {

                // 创建用户
                //$data->name = get_option('woocommerce_store_name'); // 获取商店名称
                $data->name = substr($data->email,0,strpos($data->email,'@'));
                $data->phone = get_option('woocommerce_store_phone'); // 获取商店电话
                $data->language = get_option('WPLANG'); // 获取语言设置

                $data->shop_code = $shop_code; // 获取商店地址，通常用作商店代码
                $data->shop_name = get_option('woocommerce_store_name'); // 获取商店名称
                $data->website = $shop_url; // 获取网站URL
                $data->address1 = get_option('woocommerce_store_address'); // 获取商店地址
                $data->address2 = ''; // 这个属性不是从 WooCommerce 配置中获取的
                $data->city = get_option('woocommerce_store_city'); // 获取商店城市
                $data->state = get_option('woocommerce_default_country'); // 获取商店默认国家/地区
                $data->zip = get_option('woocommerce_store_postcode'); // 获取商店邮政编码
                $data->country = get_option('woocommerce_default_country'); // 获取商店默认国家/地区
                $data->currency = get_woocommerce_currency(); // 获取商店货币设置
                $data->timezone = get_option('timezone_string'); // 获取时区设置
                $data->multi_location_enabled = true; // 这个属性不是从 WooCommerce 配置中获取的
                $data->cookie_consent_level = 'explicit'; // 获取Cookie同意级别
                $data->tags = array(); // 这个属性不是从 WooCommerce 配置中获取的

                // 注册时自动订阅免费计划所需字段
                $data->plan_id = 11;

                $result = Helper::create_erp_user(json_encode($data));

                if ($result->status) {
                    // 保存 erp user password
                    update_option("wgc_erp_user_password", $data->password);
                    $this->create_website();
                }
            }
        }

        wp_send_json($result, $result->code);
    }


    private function create_website()
    {

        $shop_url = home_url();

        $host = parse_url($shop_url, PHP_URL_HOST);
        $shop_code = explode(".", $host)[0];
        $shop_url = str_replace('http://', '', $shop_url);
        $shop_url = str_replace('https://', '', $shop_url);

        $params = array(
            "email" => get_option('admin_email'),
            "password" => get_option("wgc_erp_user_password"),
            "website_code" => $shop_code,
            "platform_type" => 1,
            "url" => $shop_url
        );

        $result = Helper::my_website(json_encode($params));

        $api_key =  $result->data;
        if (!$api_key) {
            $result = Helper::create_my_website(json_encode($params));
            if ($result->status) {
                $api_key = $result->data->api_key;
            }
        }
        update_option("wgc_api_key", $api_key);
        return $result;
    }

    public function create_my_website()
    {

        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);
        update_option("wgc_erp_user_password", $data->password);
        $result  = $this->create_website();
        wp_send_json($result, $result->code);
    }
}
