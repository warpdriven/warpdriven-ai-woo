<?php
/*
Plugin Name: WarpDriven AI
Plugin URI: https://warp-driven.com/plugins/warpdriven-ai
Description: WarpDriven Visually Similar Recommendations
Author: Warp Driven Technology
Author URI: https://warp-driven.com/
Text Domain: warpdriven-ai
Domain Path: /languages/
Version: 1.1.0
*/

if (!defined('ABSPATH')) {
    exit();
}

define("WARPDRIVEN_AI_VERSION", "1.0.3");

class WDCreateApiMain{

	public function __construct()
    {
        if (in_array("woocommerce/woocommerce.php", apply_filters("active_plugins", get_option("active_plugins")))) {
            add_action('wp_ajax_create_rest_api_key', array($this, "create_api"));
        }
        register_setting("warp-driven-settings-group", "wd_consumer_key");
        register_setting("warp-driven-settings-group", "wd_consumer_secret");
    }

    public function create_api(){

        global $wpdb;

        try{    
            $consumer_key    = 'ck_' . wc_rand_hash();
            $consumer_secret = 'cs_' . wc_rand_hash();

            
            $users_query = new WP_User_Query( array( 
                    'role' => 'administrator', 
                    'orderby' => 'display_name'
                ) );  // query to get admin users
            $results = $users_query->get_results();
            if ($results) {    

                if($results&&count($results) > 0){
                    $user = $results[0];

                    $data = array(
                        'user_id'         => $user->ID,
                        'description'     => "VS_REST_API_KEY",
                        'permissions'     => "read_write",
                        'consumer_key'    => wc_api_hash( $consumer_key ),
                        'consumer_secret' => $consumer_secret,
                        'truncated_key'   => substr( $consumer_key, -7 ),
                    );
        
                    $wpdb->insert(
                        $wpdb->prefix . 'woocommerce_api_keys',
                        $data,
                        array(
                            '%d',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                        )
                    );
        
                    if ( 0 === $wpdb->insert_id ) {
                        throw new Exception( __( 'There was an error generating your API Key.', 'woocommerce' ) );
                    }
        
                    $key_id                      = $wpdb->insert_id;
                    $response                    = $data;
                    $response['consumer_key']    = $consumer_key;
                    $response['consumer_secret'] = $consumer_secret;
                    $response['message']         = __( 'API Key generated successfully. Make sure to copy your new keys now as the secret key will be hidden once you leave this page.', 'woocommerce' );
                    $response['revoke_url']      = '<a style="color: #a00; text-decoration: none;" href="' . esc_url( wp_nonce_url( add_query_arg( array( 'revoke-key' => $key_id ), admin_url( 'admin.php?page=wc-settings&tab=advanced&section=keys' ) ), 'revoke' ) ) . '">' . __( 'Revoke key', 'woocommerce' ) . '</a>';
                    
                    delete_option("wd_consumer_key");
                    add_option("wd_consumer_key",$consumer_key);
                    delete_option("wd_consumer_secret");
                    add_option("wd_consumer_secret",$consumer_secret);
                }

            }else{
                throw new Exception( __( 'Not find Sa user.', 'woocommerce' ) );
            }

        } catch ( Exception $e ) {
            wp_send_json( array( 'message' => $e->getMessage() ) );
        }
        wp_send_json($response);
        
    }

}

$WdCreateApiMain = new WDCreateApiMain();
