<?php
/**
 * Plugin Name: My Plugin// Tên của plugin
 * Plugin URI: http://hocwp.net // Địa chỉ trang chủ của plugin
 * Description: Đây là plugin đầu tiên mà tôi viết dành riêng cho WordPress, chỉ để học tập mà thôi. // Phần mô tả cho plugin
 * Version: 1.0 // Đây là phiên bản đầu tiên của plugin
 * Author: Bui Quang Duy // Tên tác giả, người thực hiện plugin này
 * Author URI: http://sauhi.com // Địa chỉ trang chủ của tác giả
 * License: GPLv2 or later // Thông tin license của plugin, nếu không quan tâm thì bạn cứ để GPLv2 vào đây
 */
 
if(!class_exists('My_Plugin')) {
        class My_Plugin {
                function __construct() {
                        if(!function_exists('add_shortcode')) {
                                return;
                        }
                        add_shortcode( 'hello' , array(&$this, 'hello_func') );
                }
 
                function hello_func($atts = array(), $content = null) {
                        extract(shortcode_atts(array('name' => 'World'), $atts));
                        return '<div><p>Hello '.$name.'!!!</p></div>';
                }
        }
}
function mfpd_load() {
        global $mfpd;
        $mfpd = new My_Plugin();
}
add_action( 'plugins_loaded', 'mfpd_load' );
?>