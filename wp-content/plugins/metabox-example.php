<?php
/*
Plugin Name:Meta Box Demo
Author: Quang Duy
Description: Hướng dẫn tạo meta box
Author URI: https://google.com
*/

function demo_meta_box() {
	add_meta_box('thong-tin', 'thong tin ung dung', 'quangduy_thongtin_output', 'post');
}
add_action( 'add_meta_boxes', 'demo_meta_box' );

function quangduy_thongtin_output( $post )
{
	$link_download = get_post_meta( $post->ID, '_link_download', true );
// Tạo trường Link Download
	echo ( '<label for="link_download">Link Download: </label>' );
	echo ('<input type="text" id="link_download" name="link_download" value="'.esc_attr( $link_download ).'" />');
}

/**
Lưu dữ liệu meta box khi nhập vào
@param post_id là ID của post hiện tại
 **/
function quangduy_thongtin_save( $post_id )
{
	$link_download = sanitize_text_field( $_POST['link_download'] );
	update_post_meta( $post_id, '_link_download', $link_download );
}
add_action( 'save_post', 'quangduy_thongtin_save' );