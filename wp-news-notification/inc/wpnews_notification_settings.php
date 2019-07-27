<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(isset( $_POST['savekey'] ) && wp_verify_nonce($_REQUEST['savekey'], 'mode_save_access_key')  && current_user_can('administrator')){
	
	$option_name = 'fcm_access_key_option';
	$new_value = sanitize_text_field($_POST['access_key']);

	$option_name_1 = 'wp_news_auto_notification';
	$new_value_1 = sanitize_text_field($_POST['post_noti']);

//Update / Save API Key
	if ( get_option( $option_name ) !== false ) {
		// The option already exists, so update it.
		update_option( $option_name, $new_value );
	} else {
		// The option hasn't been created yet, so add it with $autoload set to 'no'.
		$deprecated = null;
		$autoload = 'no';
		add_option( $option_name, $new_value, $deprecated, $autoload );
	}
//Update Save Auto Notification Setting.
	if ( get_option( $option_name_1 ) !== false ) {
		// The option already exists, so update it.
		update_option( $option_name_1, $new_value_1 );
	} else {
		// The option hasn't been created yet, so add it with $autoload set to 'no'.
		$deprecated = null;
		$autoload = 'no';
		add_option( $option_name_1, $new_value_1, $deprecated, $autoload );
	}

	
	//show message
	echo "<div class='update-nag updated'>
	<p>Your settings saved successfully.</p>
	</div>";
}	
?>

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

	<div class="container-contact100">
		<div class="wrap-contact100">
			<form method="post" action="admin.php?page=wp_news_push_notification_settings" enctype="multipart/form-data" class="contact100-form validate-form">
				<span class="contact100-form-title">
					Google FCM settings!
				</span>

				<div class="wrap-input100 validate-input" data-validate="API Access Key is required">
					<span class="label-input100">API Access Key</span>
					<input class="input100" type="text" name="access_key" id="access_key" value="<?php echo get_option('fcm_access_key_option'); ?>" placeholder="API access key from Google Firebase Console" required="required">
					<span class="focus-input100"></span>
					<span id="title_error" style="color:red;"></span>
				</div>

				<div class="wrap-input100 input100-select">
					<span class="label-input100">Auto New Post Notification</span>
					<div>
						<select class="selection-2" name="post_noti" id="post_noti" required="required">
							<option value="Yes" <?php if (get_option('wp_news_auto_notification') == "Yes") {
								echo 'selected="true"';
							}?>>Yes</option>
							<option value="No" <?php if (get_option('wp_news_auto_notification') == "No") {
								echo 'selected="true"';
							}?>>No</option>		
						</select>

					</div>
					<span class="focus-input100"></span>
					<span id="description_error" style="color:red;"></span>
				</div>

				<?php 
				wp_nonce_field('mode_save_access_key','savekey'); 
				?>
				<div class="container-contact100-form-btn">
					<div class="wrap-contact100-form-btn">
						<div class="contact100-form-bgbtn"></div>
						<button class="contact100-form-btn">
							<span>
								Save Settings
								<i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
							</span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div>
		<h1>How to find your google firebase access key</h1>
		<br>
		<b>STEP 1:</b> Go to <a href="https://console.firebase.google.com/" target="_blank"><b>Firebase Console</b></a>
		<br><br>
		<b>STEP 2:</b> Select your Project
		<br><br>
		<b>STEP 3:</b> Click on Settings icon and select <b>Project Settings</b>
		<br><br>
		<img src="<?php echo plugins_url( 'images/screen-1.png', dirname(__FILE__) );?>" border="0">
		<br><br>
		<b>STEP 4:</b> Select <b>CLOUD MESSAGING</b> tab->Legacy server key.
		<br><br>
		<img src="<?php echo plugins_url( 'images/screen-2.png', dirname(__FILE__) );?>" border="0">
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

