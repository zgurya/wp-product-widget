<?php 
if ( !class_exists( 'WP_Products_Wg_Meta' ) ) {
    class WP_Products_Wg_Meta{
        public function __construct() {
            add_action('add_meta_boxes', array($this,'add_products_rating'));
            add_action( 'save_post', array($this,'products_rating_save' ));
        }
        
        public function add_products_rating(){
            add_meta_box( 'myplugin_sectionid', _x('Enter product rating','wp-products-wg'), array($this,'add_products_rating_callback'), array('products') );
        }
        
        public function add_products_rating_callback($post, $meta){
            wp_nonce_field( plugin_basename(__FILE__), 'wp_products_wg_noncename' );
            
            // Поля формы для введения данных
            echo '<label for="procuct_rating">' . __('Rating', 'wp-products-wg' ) . '</label> ';
            echo '<input type="number" id= "procuct_rating" min="1" max="5" name="wp_products_wg_rating" value="'.get_post_meta( $post->ID, 'wp_products_wg_rating', true ).'" size="25" required="required"/>';
        }
        
        public function products_rating_save($post_id){
            if ( ! isset( $_POST['wp_products_wg_rating'] ) )
                return;
            if ( ! wp_verify_nonce( $_POST['wp_products_wg_noncename'], plugin_basename(__FILE__) ) )
                return;
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
                return;
            update_post_meta( $post_id, 'wp_products_wg_rating', $_POST['wp_products_wg_rating'] );
        }
    }
}

?>