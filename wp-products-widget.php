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
            register_activation_hook(__FILE__, array($this,'reset_permalinks_settings'));
            
            add_action( 'wp_loaded', array($this,'add_default_content'));
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
        
        public function reset_permalinks_settings(){
            flush_rewrite_rules();
        }
        
        public function add_default_content() {
            if ( post_type_exists( 'products' ) && !get_option('created-default-content')) {
                $terms = get_terms( array(
                    'taxonomy'      => 'products_target_groups',
                    'hide_empty'    => false,
                    'fields'        => 'count'
                ));
                $posts=get_posts(array(
                    'post_type'     => 'products',
                    'post_status'   => 'publish'
                ));
                
                if($terms==0){
                    $term=wp_insert_term(
                        'Default Target Group',
                        'products_target_groups',
                        array(
                            'description'=> '',
                            'slug' => 'default_target_groups',
                            'parent'=> ''
                        ));
                    
                    if(!is_wp_error($term) && empty($posts)){
                        add_option('created-default-content',true);
                        $post_data = array(
                            'post_title'    => _x('Default product','wp-products-wg'),
                            'post_status'   => 'publish',
                            'post_author'   => 1,
                            'post_type'     => 'products'
                        );
                        $post_id = wp_insert_post( $post_data );
                        if($post_id){
                            wp_set_object_terms($post_id, $term['term_id'], 'products_target_groups');
                            $filename = dirname( __FILE__).'/default.jpg';
                            $upload_file = wp_upload_bits(basename($filename), null, file_get_contents($filename));
                            if (!$upload_file['error']) {
                                $wp_upload_dir = wp_upload_dir();
                                $wp_filetype = wp_check_filetype($filename, null );
                                $attachment = array(
                                    'guid'              => $wp_upload_dir['url'] . '/' . basename($upload_file['file']),
                                    'post_mime_type'    => $wp_filetype['type'],
                                    'post_parent'       => $post_id,
                                    'post_title'        => preg_replace('/\.[^.]+$/', '', basename($filename)),
                                    'post_content'      => '',
                                    'post_status'       => 'inherit'
                                );
                                $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $post_id, true );
                                if (!is_wp_error($attachment_id)) {
                                    require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                                    wp_update_attachment_metadata( $attachment_id,  $attachment_data );
                                    set_post_thumbnail( $post_id, $attachment_id );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    new WP_Products_Wg();
}
?>