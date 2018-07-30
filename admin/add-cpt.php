<?php 
if ( !class_exists( 'WP_Products_Wg_CTP' ) ) {
    class WP_Products_Wg_CTP{
        
        public function __construct() {
            
            add_action( 'init', array($this,'add_products_ctp'));
            add_action( 'init', array($this,'add_products_tax'));
            
        }
        
        public function add_products_ctp() {
            $labels = array(
                'name' => _x( 'Products', 'wp-products-wg' ),
                'singular_name' => _x( 'Product', 'wp-products-wg' ),
                'add_new' => _x( 'Add New', 'wp-products-wg' ),
                'add_new_item' => _x( 'Add New Product', 'wp-products-wg' ),
                'edit_item' => _x( 'Edit Product', 'wp-products-wg' ),
                'new_item' => _x( 'New Product', 'wp-products-wg' ),
                'view_item' => _x( 'View Product', 'wp-products-wg' ),
                'search_items' => _x( 'Search Products', 'wp-products-wg' ),
                'not_found' => _x( 'No products found', 'wp-products-wg' ),
                'not_found_in_trash' => _x( 'No products found in Trash', 'wp-products-wg' ),
                'parent_item_colon' => _x( 'Parent Product:', 'wp-products-wg' ),
                'menu_name' => _x( 'Products', 'wp-products-wg' ),
            );
            
            $args = array(
                'labels' => $labels,
                'hierarchical' => true,
                'description' => 'Products',
                'supports' => array( 'title', 'thumbnail'),
                'taxonomies' => array( 'target_groups' ),
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 5,
                'menu_icon' => 'dashicons-cart',
                'show_in_nav_menus' => true,
                'publicly_queryable' => true,
                'exclude_from_search' => false,
                'has_archive' => true,
                'query_var' => true,
                'can_export' => true,
                'rewrite' => true,
                'capability_type' => 'post'
            );
            
            register_post_type( 'products', $args );
        }
        
        public function add_products_tax() {
            $labels = array(
                'name'                => _x( 'Target Groups', 'wp-products-wg' ),
                'menu_name'           => _x( 'Target Groups', 'wp-products-wg' ),
                'view_item'           => _x( 'View Target Group', 'wp-products-wg' ),
                'add_new_item'        => _x( 'Add New Target Group', 'wp-products-wg' ),
                'add_new'             => _x( 'Add New Target Group', 'wp-products-wg' ),
                'edit_item'           => _x( 'Edit Target Groups Category', 'wp-products-wg' ),
                'update_item'         => _x( 'Update Target Group', 'wp-products-wg' ),
                'search_items'        => _x( 'Search Target Group', 'wp-products-wg' ),
                'not_found'           => _x( 'Target Group not found', 'wp-products-wg' ),
                'not_found_in_trash'  => _x( 'Target Group not found in bin', 'wp-products-wg' ),
            );
            
            $args = array(
                'label'               => _x( 'Target Groups', 'wp-products-wg' ),
                'description'         => _x( 'Target Groups', 'wp-products-wg' ),
                'labels'              => $labels,
                'rewrite'   		  => array( 'slug' => 'products_target_groups', 'with_front' => false ),
                'hierarchical'		  => false,
                'has_archive'		  => true
            );
            
            register_taxonomy(
                'products_target_groups','products', $args
            );
        }
        
        
    }
}
?>