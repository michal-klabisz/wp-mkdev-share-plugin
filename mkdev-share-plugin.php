<?php
/*
Plugin Name: MKDev Share Plugin
=
Description: Facebook And LinkedIn Share Plugin.
Version: 1.0.7
Author: Michał Klabisz
License: GPLv2 or later
Text Domain: mkdev-share-plugin
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Plugin Text Domain
define("MKDEV_TXTDM","mkdev-share-plugin");

define("MKDEV_PLUGIN_PATH", plugin_dir_url( __FILE__ ) );

define("MKDEV_OPTIONS", "mkdev_share_button_settings");

require("mkdev-share-setting-page.php");

//Default settings
register_activation_hook( __FILE__, 'mkdev_share_defaultsettings' );
function mkdev_share_defaultsettings() {
	$mkdev_default_settings = array(
		"share_fb_btn_layout" 	     => "button",
		"share_fb_btn_mobile_frame" => true,
		"share_fb_link_text" => "Facebook Share",
		"share_in_btn_layout" 	     => "button",
		"share_in_link_text" => "LinkedIn Share",
		"share_color" => "#000000",
		"share_btn_page_link"	 => false,
	);
	add_option( MKDEV_OPTIONS, $mkdev_default_settings );
}

function initialize_share_scripts($content) {
?>

	<script>
		(function(d, s, id) {
		    var js, fjs = d.getElementsByTagName(s)[0];
		    if (d.getElementById(id)) return;
		    js = d.createElement(s); js.id = id;
		    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
		    fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
<?php
}

add_action( 'wp_head', 'initialize_share_scripts' );

//Adding Facebook Buttons After Page Content
function share_mkdev_page_shortcode($atts = []){
	require("mkdev-globals.php");

	// normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);

	//get setting of buttons
	$all_settings = get_option(MKDEV_OPTIONS);

	$share_id = isset($atts['id']) ? $atts['id'] : false;

	$share_btn_url = isset($atts['url']) ? $atts['url'] : $all_settings['share_btn_page_link'];

	if ($share_btn_url && strpos($share_btn_url, 'http') === false) {
		$share_btn_url = "http://" . $share_btn_url;
	}

	//wp_enqueue_style( 'fb-bootstrap-css',MKDEV_PLUGIN_PATH.'css/fb-buttons-bootstrap.css' );
	wp_enqueue_style( 'in-bootstrap-css',MKDEV_PLUGIN_PATH.'css/in-buttons.css' );

	if ( $share_btn_url ) {

		$content =  "<div class='row'><div " . ($share_id ? "id='ef-share-wrapper-$share_id'" : "") . " class='col-md-12 mkdev-share-wrapper' style='text-align:center'>";

		//fb share button
		if(isset($share_btn_url)) {
			$share_fb_btn_layout       = isset($atts['fb_layout']) && in_array($atts['fb_layout'], $fb_layouts) ? $atts['fb_layout'] : $all_settings['share_fb_btn_layout'];
			$share_fb_btn_mobile_frame = $all_settings['share_fb_btn_mobile_frame'] ? $all_settings['share_fb_btn_mobile_frame'] : true;
			$share_fb_link_text		   = $all_settings['share_fb_link_text'] ? $all_settings['share_fb_link_text'] : "Facebook Share";
			$share_in_btn_layout       = isset($atts['in_layout']) && in_array($atts['in_layout'], $in_layouts) ? $atts['in_layout'] : $all_settings['share_in_btn_layout'];
			$share_in_link_text		   = $all_settings['share_in_link_text'] ? $all_settings['share_in_link_text'] : "LinkedIn Share";

			$share_color 	   = isset($atts['color']) && $atts['color'] ? $atts['color'] : ($all_settings['share_color'] ? $all_settings['share_color'] : "#000000");

			if ($share_fb_btn_layout === "icon" || $share_in_btn_layout === "icon" || $share_fb_btn_layout === "link" || $share_in_btn_layout === "link") {
				$content .= "<style>.fusion-social-network-icon::before, .fusion-social-network-icon, .mkdev-share-link:before { color: $share_color; } .mkdev-share-link:link, .mkdev-share-link:visited, .mkdev-share-link:hover, .mkdev-share-link:active { color: $share_color; } </style>";
			}

			$content .= '<div ' . ($share_id ? "id='ef-fb-share-wrapper-$share_id'" : "") . 'class="mkdev-fb-share-wrapper" style="display: inline-block; margin-right: 25px">';

			/* Link only */
			if ($share_fb_btn_layout == 'link') {
			 	$content .= '<a '. ($share_id ? "id='ef-fb-share-link-$share_id'" : "") . ' class="mkdev-share-link mkdev-fb-share-link" href="https://www.facebook.com/sharer/sharer.php?kid_directed_site=0&u=' . $share_btn_url . '&display=popup&ref=plugin&src=share_button" rel="noopener noreferrer" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=800,width=500,left=0,top=0\');return false;">' . $share_fb_link_text . '</a>';
			} else if ($share_fb_btn_layout == 'icon') {
				$content .= '<a '. ($share_id ? "id='ef-fb-share-icon-$share_id'" : "") . ' class="fusion-social-network-icon fusion-facebook fusion-icon-facebook" href="https://www.facebook.com/sharer.php?u=' . $share_btn_url . '" target="_blank" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=800,width=500,left=0,top=0\');return false;"><span class="screen-reader-text">Facebook</span></a>';
			} else {
				/* Build button */
		   		$content .= '<div ' . ($share_id ? "id='ef-fb-share-button-$share_id'" : "") . ' class="fb-share-button" data-href="' . $share_btn_url . '" data-size="' . 'small' . '" data-layout="' . $share_fb_btn_layout . '" data-mobile-iframe="' . $share_fb_btn_mobile_frame . '"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=$share_btn_url" class="fb-xfbml-parse-ignore">Share</a></div>';
			}

			$content .= "</div>";

			$content .= '<div ' . ($share_id ? "id='ef-in-share-wrapper-$share_id'" : "") . ' class="mkdev-in-share-wrapper" style="display: inline-block; margin-left: 25px">';

			/* Link only */
			if ($share_in_btn_layout == 'link') {
				$content .= "<a " . ($share_id ? "id='ef-in-share-link-$share_id'" : "") . " class='mkdev-share-link mkdev-in-share-link' href='https://www.linkedin.com/shareArticle?mini=true&amp;url=$share_btn_url' rel='noopener noreferrer' onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=500,left=0,top=0'); return false;\">$share_in_link_text</a>";
			} else if ($share_in_btn_layout == 'icon') {
				$content .= "<a " . ($share_id ? "id='ef-in-share-icon-$share_id'" : "") . " class='fusion-social-network-icon fusion-tooltip fusion-linkedin fusion-icon-linkedin fusion-last-social-icon'  href='https://www.linkedin.com/shareArticle?mini=true&amp;url=$share_btn_url' target='_blank' rel='noopener noreferrer' onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=500,left=0,top=0'); return false;\"><span class='screen-reader-text'>Linkedin</span></a>";
			} else {
				$content .= '<span ' . ($share_id ? "id='ef-in-share-button-$share_id'" : "") . ' class="IN-widget" style="display: inline-block; line-height: 1; vertical-align: bottom; padding: 0px; margin: 0px; text-indent: 0px; text-align: center;"><span style="padding: 0px !important; margin: 0px !important; text-indent: 0px !important; display: inline-block !important; vertical-align: bottom !important; font-size: 1px !important;"><button class="IN-2bc0215c-7188-4274-b598-1969e06d4d7c-1G9ISYhSF8XoOmdcl0yKDu" onclick=\'window.open("https://www.linkedin.com/shareArticle?mini=true&url=' . $share_btn_url . '", "mywin",
"left=20,top=20,width=500,height=600,toolbar=1,resizable=0,left=0,top=0"); return false;\'><xdoor-icon aria-hidden="true"><svg viewBox="0 0 24 24" width="24px" height="24px" x="0" y="0" preserveAspectRatio="xMinYMin meet">
			      <g style="fill: currentColor">
			        <rect x="-0.003" style="fill:none;" width="24" height="24"></rect>
			        <path style="" d="M20,2h-16c-1.1,0-2,0.9-2,2v16c0,1.1,0.9,2,2,2h16c1.1,0,2-0.9,2-2V4C22,2.9,21.1,2,20,2zM8,19h-3v-9h3V19zM6.5,8.8C5.5,8.8,4.7,8,4.7,7s0.8-1.8,1.8-1.8S8.3,6,8.3,7S7.5,8.8,6.5,8.8zM19,19h-3v-4c0-1.4-0.6-2-1.5-2c-1.1,0-1.5,0.8-1.5,2.2V19h-3v-9h2.9v1.1c0.5-0.7,1.4-1.3,2.6-1.3c2.3,0,3.5,1.1,3.5,3.7V19z"></path>
			      </g>
			    </svg></xdoor-icon>Share</button></span></span>';
			}

			$content .= "</div>";
		}

		$content .= "</div></div>";
	}
	return $content;
}

add_shortcode( 'share_mkdev_page', 'share_mkdev_page_shortcode' );

?>
