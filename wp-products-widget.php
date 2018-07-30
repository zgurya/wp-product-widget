<?php
/**
* Plugin Name: WordPress Products Widget
* Plugin URI: https://github.com/zgurya/wp-products-widget
* Description: This plugin allows you manage products in widget.
* Version: 0.1
* Author: a.zgurya
* Author URI: https://www.facebook.com/a.zgurya
*/

if(! defined( 'WPINC' ) || !defined('ABSPATH')) die;


if ( !class_exists( 'WP_Products_Wg' ) ) {
    class WP_Products_Wg{

        public function __construct() {
            add_action('init', array($this,'check_start_session'), 1);
            load_plugin_textdomain( 'wp-products-wg', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
            
            require_once plugin_dir_path( __FILE__ ) . 'admin/add-meta-box.php';
            require_once plugin_dir_path( __FILE__ ) . 'admin/add-cpt.php';
            require_once plugin_dir_path( __FILE__ ) . 'admin/add-settings.php';
            require_once plugin_dir_path( __FILE__ ) . 'admin/widget.php';
            
            new WP_Products_Wg_CTP();
            new WP_Products_Wg_Meta();
            new WP_Products_Wg_Settings();
            
            add_action( 'widgets_init', array($this,'widget_init') );
            add_action('admin_enqueue_scripts', array($this,'add_admin_scripts'));
            add_action('wp_enqueue_scripts', array($this,'add_public_scripts'));
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this,'add_settings_links'));
        }
        
        public function check_start_session() {
            if(!session_id()) {
                session_start();
            }
            
            if(isset($_GET['target'])&&!empty($_GET['target'])){
                $_SESSION['wp_products_wg_target']=$_GET['target'];
            }
        }
        
        public function widget_init() {
            register_widget( 'WP_Products_Wg_Widget' );
        }

        public function add_admin_scripts(){
            wp_enqueue_style('wp-products-wg-styles', plugin_dir_url( __FILE__ ).('admin/css/style.css'));
        }
        
        public function add_public_scripts(){
            wp_enqueue_style('wp-products-wg-styles', plugin_dir_url( __FILE__ ).('public/css/style.css'));
            wp_enqueue_style('wp-products-wg-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
        }
        
        public function add_settings_links($links) {
            array_push($links, '<a href="'. esc_url( get_admin_url(null, 'themes.php?page=wp_products_wg_settings') ) .'">'._x('Settings','wp-products-wg').'</a>');
            return $links;
        }
    }
    new WP_Products_Wg();
}
?>