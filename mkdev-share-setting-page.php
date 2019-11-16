<?php
//save settings
if (isset($_POST['mkdev_action'])){
	if (update_option(MKDEV_OPTIONS, $_POST)) {
		echo "yes";
	} else {
		echo "no";
	}
	die ;
} // end of save if

// Setting Page
add_action( 'admin_menu', 'mkdev_social_button_menu' );
function mkdev_social_button_menu () {
	add_menu_page ( __( 'MKDev Share Settings', MKDEV_TXTDM ),  __( 'MKDev Share Settings', MKDEV_TXTDM ),  'administrator', 'mkdev-share-setting', 'mkdev_button_settings_admin_page', 'dashicons-facebook-alt' );
}

function mkdev_button_settings_admin_page (){
	require("mkdev-globals.php");

	wp_enqueue_style( 'fb-bootstrap-css',MKDEV_PLUGIN_PATH.'css/fb-buttons-bootstrap.css' );
	wp_enqueue_style( 'fb-font-awesome-css',MKDEV_PLUGIN_PATH.'css/font-awesome.css' );
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'bootstrap-js', MKDEV_PLUGIN_PATH.'js/bootstrap.js', '(jquery)');
	wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'mkdev-share', MKDEV_PLUGIN_PATH.'js/mkdev-share.js', array( 'wp-color-picker' ), false, true );

	//toggle button CSS
	wp_enqueue_style('awl-toogle-button-css', MKDEV_PLUGIN_PATH . 'css/toggle-button.css');

	//get values from database
	$share_button_settings = get_option(MKDEV_OPTIONS);

	//fb share button
	if(isset($share_button_settings['share_fb_btn_layout'])){
		$share_fb_btn_layout = $share_button_settings['share_fb_btn_layout'];
	} else { $share_fb_btn_layout = 'button'; }

	if(isset($share_button_settings['share_fb_link_text']) && $share_button_settings['share_fb_link_text']){
		$share_fb_link_text = $share_button_settings['share_fb_link_text'];
	} else { $share_fb_link_text = 'Facebook Share'; }

	if(isset($share_button_settings['share_fb_btn_mobile_frame'])){
		$share_fb_btn_mobile_frame = $share_button_settings['share_fb_btn_mobile_frame'];
	} else { $share_fb_btn_mobile_frame = 'true'; }

	if(isset($share_button_settings['share_fb_btn_lang'])){
		$share_fb_btn_lang = $share_button_settings['share_fb_btn_lang'];
	} else { $share_fb_btn_lang = 'en_US'; }

	// linkedin share button

	if(isset($share_button_settings['share_in_btn_layout'])){
		$share_in_btn_layout = $share_button_settings['share_in_btn_layout'];
	} else { $share_in_btn_layout = 'button'; }

	if(isset($share_button_settings['share_in_link_text']) && $share_button_settings['share_in_link_text']){
		$share_in_link_text = $share_button_settings['share_in_link_text'];
	}  else { $share_in_link_text = 'LinkedIn Share'; }

	// common settings
	if(isset($share_button_settings['share_btn_page_link'])){
		$share_btn_page_link = $share_button_settings['share_btn_page_link'];
	} else { $share_btn_page_link = false; }

	if(isset($share_button_settings['share_color'])){
		$share_color = $share_button_settings['share_color'];
	} else { $share_color = "#000000"; }

	$args = array(
		'sort_order' => 'asc',
		'sort_column' => 'post_title',
		'hierarchical' => 1,
		'exclude' => '',
		'include' => '',
		'meta_key' => '',
		'meta_value' => '',
		'authors' => '',
		'child_of' => 0,
		'parent' => -1,
		'exclude_tree' => '',
		'number' => '',
		'offset' => 0,
		'post_type' => 'page',
		'post_status' => 'publish'
	);
	$pages = get_pages($args);

?>
	<style>
		.col-md-2 {
			float:left;
			width: 16.6667%;
		}

		.like{
			font-size:18px;
		}
		.content{

		}
		.fb-button-heading{
			font-weight:bold;
		}
		.fb_icons{
			color:#0073AA;
		}
		.content{
			font-family: 'Josefin Sans', sans-serif !important;
		}
		.fb-menu li a{
			text-decoration: none !important;
		}
		.fb-button-page{
			background-color:#FFFFFF;
			margin-bottom: 20px;
			min-height: 20px;
			padding: 19px;

			 box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);

		}
		.fb-button-heading{
			margin-top:20px;

		}
	</style>

<div class="container text-left" id="fb-button-div">
	<div class="panel panel-default fb-button-heading">
		<h2 class="text-center"><?php _e('MKDev Buttons Setting', MKDEV_TXTDM); ?></h2>
	</div>
	<div class="fb-button-page">
		<ul class="nav nav-pills fb-menu">
			<li class="active"><a data-toggle="pill" href="#fb-btn-share"><?php _e('Facebook Share', MKDEV_TXTDM); ?></a></li>
			<li><a data-toggle="pill" href="#in-btn-share"><?php _e('LinkedIn Share', MKDEV_TXTDM); ?></a></li>
		</ul>
		<form id="fb-setting-form">
			<div class="tab-content">
				<!-- Facebook Share Button -->
				<div id="fb-btn-share" class="tab-pane fade in active">
					<div class="row">
						<p class="col-xs-3">
							<label for=""><?php _e('Button Layout', MKDEV_TXTDM); ?></label>
							<select class="widefat" id="share_fb_btn_layout" name="share_fb_btn_layout">
								<option value="box_count" <?php if ($share_fb_btn_layout == 'box_count') echo 'selected="selected"'; ?>><?php _e('Box Count', MKDEV_TXTDM); ?></option>
								<option value="button_count" <?php if ($share_fb_btn_layout == 'button_count') echo 'selected="selected"'; ?>><?php _e('Button Count', MKDEV_TXTDM); ?></option>
								<option value="button" <?php if ($share_fb_btn_layout == 'button') echo 'selected="selected"'; ?>><?php _e('Button', MKDEV_TXTDM); ?></option>
								<option value="icon" <?php if ($share_fb_btn_layout == 'icon') echo 'selected="selected"'; ?>><?php _e('Icon', MKDEV_TXTDM); ?></option>
								<option value="link" <?php if ($share_fb_btn_layout == 'link') echo 'selected="selected"'; ?>><?php _e('Link', MKDEV_TXTDM); ?></option>
							</select>
						</p>
					</div>
					<div class="row" style="<?php if ($share_fb_btn_layout !== "link") echo "display:none" ?>">
						<p class="col-xs-3">
							<label for=""><?php _e('Link Custom Text', MKDEV_TXTDM); ?></label>
							<input type="text" class="widefat" id="share_fb_link_text" name="share_fb_link_text" value="<?php echo $share_fb_link_text ?>"/>
						</p>
					</div>
					<div class="row">&nbsp;&nbsp;&nbsp;&nbsp;
						<label for=""><?php _e('Enable Mobile Frame', MKDEV_TXTDM); ?></label><br>
						<p class="col-xs-6 switch-field em_size_field">
							<input class="widefat" id="share_fb_btn_mobile_frame1" name="share_fb_btn_mobile_frame" type="radio" value="true" <?php if($share_fb_btn_mobile_frame == true) echo "checked=checked"; ?>>
							<label for="share_fb_btn_mobile_frame1"><?php _e('Yes', MKDEV_TXTDM); ?></label>
							<input class="widefat" id="share_fb_btn_mobile_frame2" name="share_fb_btn_mobile_frame" type="radio" value="false" <?php if($share_fb_btn_mobile_frame == false) echo "checked=checked"; ?>>
							<label for="share_fb_btn_mobile_frame2"><?php _e('No', MKDEV_TXTDM); ?></label>
						</p>
					</div>

				</div>
				<!-- LinkedIn Share Button -->
				<div id="in-btn-share" class="tab-pane fade">
					<div class="row">
						<p class="col-xs-3">
							<label for=""><?php _e('Button Layout', MKDEV_TXTDM); ?></label>
							<select class="widefat" id="share_in_btn_layout" name="share_in_btn_layout">
								<option value="button" <?php if ($share_in_btn_layout == 'button') echo 'selected="selected"'; ?>><?php _e('Button', MKDEV_TXTDM); ?></option>
								<option value="icon" <?php if ($share_in_btn_layout == 'icon') echo 'selected="selected"'; ?>><?php _e('Icon', MKDEV_TXTDM); ?></option>
								<option value="link" <?php if ($share_in_btn_layout == 'link') echo 'selected="selected"'; ?>><?php _e('Link', MKDEV_TXTDM); ?></option>
							</select>
						</p>
					</div>
					<div class="row" style="<?php if ($share_in_btn_layout !== "link") echo "display:none" ?>">
						<p class="col-xs-3">
							<label for=""><?php _e('Link Custom Text', MKDEV_TXTDM); ?></label>
							<input type="text" class="widefat" id="share_in_link_text" name="share_in_link_text" value="<?php echo $share_in_link_text ?>"/>
						</p>
					</div>
				</div>
				<div class="row" style="<?php if ($share_fb_btn_layout !== "icon" && $share_fb_btn_layout !== "link" && $share_in_btn_layout !== "icon" && $share_in_btn_layout !== "link") echo "display:none" ?>">
					<p class="col-xs-3">
						<label for=""><?php _e('Icons & Links Color', MKDEV_TXTDM); ?></label>
						<input type="text" class="widefat" id="share_color" name="share_color" value="<?php echo $share_color ?>"/>
					</p>
				</div>
				<div class="row">
					<p class="col-xs-3">
						<label for=""><?php _e('Share Page', MKDEV_TXTDM); ?></label>
						<select class="widefat" id="share_btn_page_link" name="share_btn_page_link">
							<option value="" <?php if (!$share_btn_page_link) echo 'selected="selected"'; ?>>Select one</option>
							<?php foreach ($pages as $page):
								$page_url = get_permalink( $page->ID ); ?>
								<option value="<?php echo $page_url ?>" <?php if ($share_btn_page_link === $page_url) echo 'selected="selected"'; ?>><?php if ($page->post_parent) { echo "&mdash; "; }  _e($page->post_title, MKDEV_TXTDM); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
				</div>
					<h4>Use this shortcode to show facebook and linkedin share button:</h4>
					<h2>[share_mkdev_page]</h2>
					<h3>Optional parameters: </h3>
					<ul style="margin-top: -10px">
						<li><strong>id</strong> - assign unique ID to HTML elements (for easier CSS styling)</li>
						<li><strong>url</strong> - override selected page with custom url</li>
						<li><strong>color</strong> - set color for share icons or links (using css syntax, f.ex rgba(255, 255, 255, 0.5), #3e3e3e, blue)</li>
						<li><strong>fb_layout</strong> - override selected facebook layout: [<?php echo implode(", ", $fb_layouts); ?>]</li>
						<li><strong>in_layout</strong> - override selected linkedin layout: [<?php echo implode(", ", $in_layouts); ?>]</li>
					</ul>
				<div id="loading_icon" name="loading_icon" style="display:none;">
					<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
					<span class=""><?php _e('Please wait...', MKDEV_TXTDM); ?></span>
				</div>
				<button type="button" id="save_setting" class="btn btn-info" onclick="SaveSettings();"><?php _e('Save', MKDEV_TXTDM); ?></button>
			</div>
		</form>
	</div>
</div>
	<?php

} // end of setting page fuction

?>
