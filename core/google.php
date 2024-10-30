<?php
function lime_google_title(){
	return "Google Toolkit";
}
function lime_google_description(){
	return "The Google Toolkit is designed to help your WordPress installation to integrate with Google with the greatest of ease.";
}
function lime_google_comment_text( $lime, $content ) {
	// AdSense comment rewrite
	if ( !is_feed() && get_option( 'lime_adsense_tag_comments' ) ) {
		return '<!-- google_ad_section_start -->'.$content.'<!-- google_ad_section_end -->';
	}
	
	return $content;
}
function lime_google_the_excerpt( $lime, $content ) {
	// AdSense excerpt rewrite
	if ( !is_feed() && get_option( 'lime_adsense_tag_posts' ) ) {
		$content = '<!-- google_ad_section_start -->'.$content.'<!-- google_ad_section_end -->';
	}
	
	return $content;
}
function rewrite_rss_link( $matches ) {
	$url = $matches[2];
	if ( preg_match( '/^https?:/', $url ) ) {
		// Tag links from this blog only
		$blogurl = get_option( 'siteurl' );
		if ( substr( $url, 0, strlen( $blogurl ) ) == $blogurl ) {
			return $matches[1].$this->tag_rss_link( $url, true );
		} else {
			return $matches[1].$url;
		}
	} else {
		return $matches[1].$this->tag_rss_link( $url, true );
	}
}

function lime_google_the_content( $lime, $content ){
	// RSS tagging - tag links in RSS' text
	if ( is_feed() && get_option( 'lime_rss_tagging' ) ) {
		$content = preg_replace_callback( '/(<\s*a\s[^>]*?\bhref\s*=\s*")([^"]+)/', 
			'rewrite_rss_link', $content );
	}
	// AdSense content rewrite
	if ( !is_feed() && get_option( 'lime_adsense_tag_posts' ) ) {
		$content = '<!-- google_ad_section_start -->'.$content.'<!-- google_ad_section_end -->';
	}
	return $content;
}
function lime_google_the_permalink_rss( $lime, $link ) {
	if ( get_option( 'lime_rss_tagging' ) ) {
		$link = lime_google_tag_link_rss( $link, true );
	}
	return $link;
}
function lime_google_tag_link_rss( $link, $use_hash ) {
	$tag = 'utm_source='.get_option( 'lime_rss_tag_source' )
		.'&amp;utm_medium='.get_option( 'lime_rss_tag_medium' )
		.'&amp;utm_campaign='.get_option( 'lime_rss_tag_campaign' );
	if ( $use_hash ) {
		return $link.'#'.$tag;
	} elseif ( strpos( $link, '?' ) === false ) {
		return $link.'?'.$tag;
	} else {
		return $link.'&amp;'.$tag;
	}
}
function lime_google_404($lime){
	if ( ( get_option("lime_wt_mode") == 'file' ) ) {
		if ( get_option("lime_wt_file") != '' ) {
			if ( $lime->lime_fetch_root().get_option("lime_wt_file") == $_SERVER['REQUEST_URI'] ) {
				echo 'google-site-verification: ', get_option("lime_wt_file");
				exit();
			}
		}
	}
}
function lime_google_wp_footer($lime){
	$ga_id = get_option( 'lime_analytics_id' );
	$google_foot_code = array();
	if ( $ga_id != '' ) {
		$google_foot_code[] = '<script type="text/javascript">';
		$google_foot_code[] = 'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");';
		$google_foot_code[] = 'document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));';
		$google_foot_code[] = '</script>';
		$google_foot_code[] = '<script type="text/javascript">';
		$google_foot_code[] = 'try {';
		$google_foot_code[] = 'var pageTracker = _gat._getTracker("' . $ga_id . '");';
		if ( is_404() && get_option( 'lime_analytics_track_404' ) ) {
			$google_foot_code[] = 'pageTracker._trackPageview("' . get_option( 'lime_analytics_track_404_prefix' ) . 
				'?page=" + document.location.pathname + document.location.search + "&from=" + document.referrer);';
		} else {
			$google_foot_code[] = 'pageTracker._setAllowAnchor(true);';
			$google_foot_code[] = 'pageTracker._trackPageview();';
		}
		$google_foot_code[] = '} catch(err) {}</script>';
	}
	echo implode("\r\n", $google_foot_code);
}
function lime_google_wp_head($lime){
	$wt_mode = get_option( 'lime_wt_mode' );
	$wt_meta = get_option( 'lime_wt_meta' );
	$ga_id = get_option( 'lime_analytics_id' );
	$ga_adsense = get_option( 'lime_analytics_adsense' );
	$google_head_code = array();
	// Google Webmasters Tools
	if ( ( $wt_mode == 'meta_v2' ) && ( $wt_meta != '' ) ) {
		$google_head_code[] = '<meta name="google-site-verification" content="' . $wt_meta . '" />';
	}
	elseif ( ( $wt_mode == 'meta_v1' ) && ( $wt_meta != '' ) ) {
		$google_head_code[] = '<meta name="verify-v1" content="' . $wt_meta . '" />';
	}
	// Google Analytics integration with Google AdSense
	if ( ( $analytics_id != '' ) && $ga_adsense ) {
		$google_head_code[] = '<script type="text/javascript">';
		$google_head_code[] = 'window.google_analytics_uacct = "' . $analytics_id . '";';
		$google_head_code[] = '</script>';
	}
	echo implode("\r\n", $google_head_code);
}
function lime_google_settings_header($lime){
	if(!$lime->lime_mod_rewrite()){
		print $lime->lime_message($lime->last_message, "error");
	}
}
function lime_google_enqueue_scripts(){
	if(get_option( 'lime_chromeframe_enabled' )){
		wp_deregister_script( 'google-chrome-frame' );
		wp_register_script( 'google-chrome-frame', 'https://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js');
		wp_enqueue_script( 'google-chrome-frame' );
	}
}
function lime_google_options($lime){
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_mode"]["label"]					= "Page verification method";
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_mode"]["default"]					= "meta_v2";
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_mode"]["type"]						= "radio";
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_mode"]["data"]["meta_v2"]			= 'Meta tag <code>&lt;meta name="google-site-verification" content="..." /&gt;</code>';
//	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_mode"]["data"]["meta_v1"]			= 'Meta tag <code>&lt;meta name="verify-v1" content="..." /&gt;</code>';
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_mode"]["data"]["file"]				= "File";
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_meta"]["label"]					= "Meta tag value";
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_meta"]["default"]					= false;
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_meta"]["type"]						= "text";
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_file"]["label"]					= "Verification file name";
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_file"]["default"]					= false;
	$lime->options["google"]["Google Webmasters Tools"]["lime_wt_file"]["type"]						= "text";
	$lime->options["google"]["Google Analytics"]["lime_analytics_id"]["label"]						= "Google Analytics ID";
	$lime->options["google"]["Google Analytics"]["lime_analytics_id"]["default"]					= false;
	$lime->options["google"]["Google Analytics"]["lime_analytics_id"]["type"]						= "text";
	$lime->options["google"]["Google Analytics"]["lime_analytics_adsense"]["label"]					= "Enable Google AdSense integration";
	$lime->options["google"]["Google Analytics"]["lime_analytics_adsense"]["default"]				= false;
	$lime->options["google"]["Google Analytics"]["lime_analytics_adsense"]["type"]					= "check";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tagging"]["label"]					= "Enable RSS/Atom Feeds tagging";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tagging"]["default"]				= false;
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tagging"]["type"]					= "check";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tag_source"]["label"]				= "Source name";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tag_source"]["default"]			= "feed";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tag_source"]["type"]				= "text";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tag_medium"]["label"]				= "Medium name";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tag_medium"]["default"]			= "feed";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tag_medium"]["type"]				= "text";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tag_campaign"]["label"]			= "Campaign name";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tag_campaign"]["default"]			= "feed";
	$lime->options["google"]["RSS/Atom Feeds tagging"]["lime_rss_tag_campaign"]["type"]				= "text";
	$lime->options["google"]["AdSense Section Targeting"]["lime_adsense_tag_posts"]["label"]		= "Enable AdSense Section Targetting for Content";
	$lime->options["google"]["AdSense Section Targeting"]["lime_adsense_tag_posts"]["default"]		= false;
	$lime->options["google"]["AdSense Section Targeting"]["lime_adsense_tag_posts"]["type"]			= "check";
	$lime->options["google"]["AdSense Section Targeting"]["lime_adsense_tag_comments"]["label"]		= "Enable AdSense Section Targetting for Comments";
	$lime->options["google"]["AdSense Section Targeting"]["lime_adsense_tag_comments"]["default"]	= false;
	$lime->options["google"]["AdSense Section Targeting"]["lime_adsense_tag_comments"]["type"]		= "check";
	$lime->options["google"]["404 errors tracking"]["lime_analytics_track_404"]["label"]			= "Track 404 errors with Google Analytics";
	$lime->options["google"]["404 errors tracking"]["lime_analytics_track_404"]["default"]			= false;
	$lime->options["google"]["404 errors tracking"]["lime_analytics_track_404"]["type"]				= "check";
	$lime->options["google"]["404 errors tracking"]["lime_analytics_track_404_prefix"]["label"]		= "URL prefix";
	$lime->options["google"]["404 errors tracking"]["lime_analytics_track_404_prefix"]["default"]	= "/404.html";
	$lime->options["google"]["404 errors tracking"]["lime_analytics_track_404_prefix"]["type"]		= "text";
	
	$lime->options["google"]["Miscellaneous"]["lime_chromeframe_enabled"]["label"]			= "Enable Google Chrome Frame";
	$lime->options["google"]["Miscellaneous"]["lime_chromeframe_enabled"]["about"]			= "https://code.google.com/chrome/chromeframe/";
	$lime->options["google"]["Miscellaneous"]["lime_chromeframe_enabled"]["default"]			= false;
	$lime->options["google"]["Miscellaneous"]["lime_chromeframe_enabled"]["type"]				= "check";
}
?>