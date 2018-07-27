<?php 
if ( !class_exists( 'WP_Products_Wg_Settings' ) ) {
    class WP_Products_Wg_Settings{
        public function __construct() {
            add_action('admin_menu', array($this,'add_menu_settings'));
            add_action( 'admin_init', array($this,'register_settings') );
        }
        
        public function register_settings(){
            register_setting( 'wp-products-wg-group', 'default-target-group' ); 
        }
        
        public function add_menu_settings(){
            add_theme_page(_x('WordPress Products Widget Settings','wp-products-wg'), _x('WP Products Widget','wp-products-wg'), 'edit_theme_options', 'wp_products_wg_settings', array($this,'add_menu_settings_init'));
        }
        
        public function add_menu_settings_init(){?>
        	<div class="wrap">
            	<h1><?php _e('WordPress Products Widget Settings','wp-products-wg');?></h1>
                <form method="POST" action="options.php">
                	<?php settings_fields( 'wp-products-wg-group' ); ?>
    				<?php do_settings_sections( 'wp-products-wg-group' ); ?>
                	<table class="form-table">
    					<tr>
    						<th scope="row" align="right"><label for="default-target-group"><?php _e('Select default group', 'wp-products-wg')?>:</label></th>
    						<td>
    							<select id="default-target-group" name="default-target-group" required="required">
    								<option value="">--</option>
    								<?php 
    								$terms = get_terms( array(
    								    'taxonomy' => 'products_target_groups',
    								    'hide_empty' => false,
    								));
    								?>
    								<?php foreach ($terms as $term):?>
    									<option value="<?php echo $term->slug?>" <?php selected( $term->slug, get_option('default-target-group') ); ?>><?php echo $term->name;?></option>
    								<?php endforeach;?>
    							</select>
    						</td>
    					</tr>
    					<tr>
                			<th scope="row"><?php submit_button(); ?></th>
                			<td align="right"></td>
                		</tr>
    				</table>
                </form>
            </div>
        <?php 
        }
    }
}
?>