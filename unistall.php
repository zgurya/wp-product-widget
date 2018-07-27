<?php 
if(!session_id()) {
    session_start();
}
if(isset($_SESSION['wp_products_wg_target'])){
    unset($_SESSION['wp_products_wg_target']);
}
?>