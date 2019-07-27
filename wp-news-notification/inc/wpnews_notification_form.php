<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(isset( $_POST['sendmsg'] ) && wp_verify_nonce($_REQUEST['sendmsg'], 'mode_send_msg')  && current_user_can('administrator')){

	$title = sanitize_text_field($_POST['title']);
	$msg = sanitize_text_field($_POST['message']);
	$post_id = sanitize_text_field($_POST['newsId']);

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>FCM Notification Form</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link href="<?php echo plugins_url( 'fonts/font-awesome-4.7.0/css/font-awesome.min.css', dirname(__FILE__) );?>" type="text/css" rel="stylesheet" />
	<!--===============================================================================================-->
	<link href="<?php echo plugins_url( 'vendor/select2/select2.min.css', dirname(__FILE__) );?>" type="text/css" rel="stylesheet" />
	<!--===============================================================================================-->
	<link href="<?php echo plugins_url( 'css/main.css', dirname(__FILE__) );?>" type="text/css" rel="stylesheet" />
	<!--===============================================================================================-->
</head>
<body>


	<div class="container-contact100">
		<div class="wrap-contact100">
			<form id="pushform" method="POST" action="admin.php?page=wp_news_push_notification" enctype="multipart/form-data" class="contact100-form validate-form">
				<span class="contact100-form-title">
					Send Custom Push Notification!
				</span>

				<div class="wrap-input100 validate-input" data-validate="Title is required">
					<span class="label-input100">Title</span>
					<input class="input100" type="text" name="title" id="title" placeholder="Enter Your Title" required="required">
					<span class="focus-input100"></span>
					<span id="title_error" style="color:red;"></span>
				</div>

				<div class="wrap-input100 input100-select">
					<span class="label-input100">Post / News ID</span>
					<div>
						<select class="selection-2" name="newsId" id="newsId" required="required">
							
							<?php
							global $post;
							$args = array( 'numberposts' => -1, 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC');
							$posts = get_posts($args);
							foreach( $posts as $post ) : setup_postdata($post); ?>
								<option value="<?php echo $post->ID; ?>"><?php echo $post->ID." - "; the_title(); ?></option>
							<?php endforeach; ?>
						</select>

					</div>
					<span class="focus-input100"></span>
					<span id="description_error" style="color:red;"></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate = "Message is required">
					<span class="label-input100">Message</span>
					<textarea class="input100" name="message" id="message" required="required" placeholder="Your message here..."></textarea>
					<span class="focus-input100"></span>
					<span id="newsId_error" style="color:red;"></span>
				</div>
				<?php wp_nonce_field('mode_send_msg','sendmsg'); ?>
				<div class="container-contact100-form-btn">
					<div class="wrap-contact100-form-btn">
						<div class="contact100-form-bgbtn"></div>
						<button class="contact100-form-btn">
							<span>
								Send Notification
								<i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
							</span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div id="dropDownSelect1"></div>

	<!--===============================================================================================-->
	<script type='text/javascript' src='<?php echo plugins_url( 'vendor/select2/select2.min.js', dirname(__FILE__) );?>'></script>
	<script>
		$(".selection-2").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect1')
		});
	</script>
	<!--===============================================================================================-->
	<script type='text/javascript' src='<?php echo plugins_url( 'js/main.js', dirname(__FILE__) );?>'></script>

</body>
</html>