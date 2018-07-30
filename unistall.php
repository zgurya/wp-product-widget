<?php 
if(!session_id()) {
    session_start();
}
if(isset($_SESSION['wp_products_wg_target'])){
    unset($_SESSION['wp_products_wg_target']);
}
if(get_option('default-target-group')){
    delete_option('default-target-group');
}
if(get_option('created-default-content')){
    delete_option('created-default-content');
}
?>