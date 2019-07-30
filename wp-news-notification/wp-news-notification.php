<?php
/*
 * Plugin Name: WPNews - Notification
 * Plugin URI:  https://github.com/SagarMaher2/wp-news-notification
 * Description: WPNews Plugin for sending notification to android device.
 * Author:      Sagar Maher
 * Author URI:  http://codingvisions.com
 * Version:     0.0.10
 * Text Domain: wp-news-notification
 *
 * Copyright: © 2019 Coding Visions Infotech Pvt. Ltd., (sagar@codingvisions.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function cvipl_wp_news_notification_plugin_menu(){
	add_menu_page('WPNews Push Notification', 'WPNews Push Notification', 'manage_options', 'cvipl_wp_news_push_notification', 'cvipl_wp_news_push_notification', plugins_url( 'images/mail-icon.png', __FILE__  ),10);

	add_submenu_page( 'wp_news_push_notification', 'Settings', 'WPNews settings', 'manage_options', 'cvipl_wp_news_push_notification_settings', 'cvipl_wp_news_push_notification_settings');
}
add_action( 'admin_menu', 'wp_news_notification_plugin_menu' );

add_action( 'publish_post', 'wp_news_notification_notify_new_post' );

function cvipl_wp_news_push_notification(){
	include('inc/wpnews_notification_form.php');
}

function cvipl_wp_news_push_notification_settings(){
	include('inc/wpnews_notification_settings.php');
}


/*
 * Notify users sending them an push notification
 *
 * @param int $post_ID the current post ID
 */
function cvipl_wp_news_notification_notify_new_post( $post_ID ){
   
    if (get_option('wp_news_auto_notification') == "Yes"){
	$title = get_the_title( $post_ID );
	$msg = apply_filters('the_content', get_post_field('post_content', $post_ID));
	$post_id = $post_ID;

	// API access key from Google API's Console
	$apiKey = get_option('fcm_access_key_option');
	define( 'API_ACCESS_KEY', $apiKey );
	
	//firebase topic
	$to = '/topics/wpnewsnotification';
	//firebase url 
	$url = 'https://fcm.googleapis.com/fcm/send';
	// prep the bundle
	$post = array(
		'to' => $to,
		'data' => array (
			'title' => $title,
			'message' => $msg,
			'newsId' => $post_id
		)
	);

	 //send request to firebase
	$response = wp_remote_post($url, array(
		'method' => 'POST',
		'blocking' => true,
		'headers' => array( 'Authorization' => 'key='. API_ACCESS_KEY,'Content-Type' => 'application/json' ),
		'httpversion' => '1.0',
		'sslverify' => false,
		'body' => json_encode($post),
	)
);
	//response from google firebase
	$result = $response['response']['message'];
	
	// if response success
	if($result=='OK'){
		echo "<div class='update-nag updated'>
		<p>Message has been sent successfully.</p>
		</div>";
	}else{
	// Print Debug Information
		echo "<div class='update-nag error'>
		<p>".'Error: '.$response['response']['code'].'-'.$response['response']['message']."</p>
		</div>";
	}
    }

    return $post_ID;
}