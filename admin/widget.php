<?php 
class WP_Products_Wg_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
		'wp_products_wg_widget',
		__( 'Product Widget', 'wp-products-wg' ),
		array(
			'customize_selective_refresh' => true,
		)
	);
    }
    
    public function form( $instance ) {
        $defaults = array(
    		'title' => '',
            'count' => '5',
            'order' => 'ASC'
    	);
        extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>
        <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'wp-products-wg' ); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php _e( 'Products count', 'wp-products-wg' ); ?>:</label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" value="<?php echo esc_attr( $count ); ?>" />
		</p>
		<p>
			<strong><?php _e('Choose type ordering','wp-products-wg');?></strong></br>
    		<label for="<?php echo $this->get_field_id('order'); ?>">
            <?php _e('Ascending', 'wp-products-wg'); ?>:
                <input class="" id="<?php echo $this->get_field_id('order1'); ?>" name="<?php echo $this->get_field_name('order'); ?>" type="radio" value="ASC" <?php checked( $order, 'ASC' ); ?> />
            </label><br>
            <label for="<?php echo $this->get_field_id('radio_buttons'); ?>">
                <?php _e('Descending', 'wp-products-wg'); ?>:
                <input class="" id="<?php echo $this->get_field_id('order2'); ?>" name="<?php echo $this->get_field_name('order'); ?>" type="radio" value="DESC" <?php checked( $order, 'DESC' ); ?> />
            </label>
        </p>
		<?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
        $instance['count']     = isset( $new_instance['count'] ) ? wp_strip_all_tags( $new_instance['count'] ) : '';
        $instance['order']   = isset( $new_instance['order'] ) ? wp_strip_all_tags( $new_instance['order'] ) : '';
        return $instance;
    }
    
    public function widget( $args, $instance ) {
        extract( $args );
        $title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $count = isset( $instance['count'] ) ? $instance['count'] : -1;
        $order = isset( $instance['order'] ) ? $instance['order'] : 'ASC';
        
        echo $before_widget;
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        if(isset($_SESSION['wp_products_wg_target']) || get_option('default-target-group')){
            $term_slug=(isset($_SESSION['wp_products_wg_target']))?$_SESSION['wp_products_wg_target']:get_option('default-target-group');
            $posts=get_posts(
                array(
                    'post_type'         => 'products',
                    'post_status'       => 'public',
                    'posts_per_page'    => $count,
                    'meta_key'          => 'wp_products_wg_rating',
                    'orderby'          => 'meta_value_num',
                    'order'             => $order,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'products_target_groups',
                            'field' => 'slug',
                            'terms' => array($term_slug),
                            'operator' => 'IN',
                        )
                    )
                )
            );
            if($posts && !empty($posts)){
                echo '<ul>';
                foreach( $posts as $post ) : setup_postdata($post);?>
                	<li>
                		<div class="product-thumb">
                			<a href="<?php the_permalink();?>">
                				<img src="<?php echo get_the_post_thumbnail_url($post->ID, 'thumbnail'); ?>" alt="<?php the_title();?>"></div>
                			</a>
                		<div class="product-rating">
                    		<?php 
                    		for($i=1;$i<=5;$i++){
                    		    $checked=(get_post_meta( $post->ID, 'wp_products_wg_rating', true )>=$i)?'checked':'';
                    		    echo '<span class="fa fa-star '.$checked.'"></span>';
                    		}
                    		?>
                		</div>
                		<div class="product-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></div>
                	</li>
                <?php endforeach;
                echo '</ul>';
                wp_reset_postdata();
            }
        }
        echo $after_widget;
    }
    
}
?>