<?php
function lime_wpcms_title(){
	return "WordPress Core";
}
function lime_wpcms_description(){
	return "This module allows you to control WordPress Core options";
}
function lime_wpcms_settings_header(){
	print "<p>These options allow you to change global WordPress options, perfect for theme developers that do not want to support certain functions even better for whole site developers that want to restrict certain options</p>";
}
function lime_wpcms_options($lime){
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_update"]["label"] = "Hide update menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_update"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_update"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_update"]["description"] = "Switching this option on will hide the WordPress admin Update Menu, this will also remove any update nag screens";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_posts"]["label"] = "Hide posts menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_posts"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_posts"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_posts"]["description"] = "Switching this option on will hide the WordPress admin Posts Menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_media"]["label"] = "Hide media menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_media"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_media"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_media"]["description"] = "Switching this option on will hide the WordPress admin Media Menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_links"]["label"] = "Hide links menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_links"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_links"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_links"]["description"] = "Switching this option on will hide the WordPress admin Links Menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_pages"]["label"] = "Hide pages menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_pages"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_pages"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_pages"]["description"] = "Switching this option on will hide the WordPress admin Pages Menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_comments"]["label"] = "Hide comments menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_comments"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_comments"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_comments"]["description"] = "Switching this option on will hide the WordPress admin Comments Menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_appearance"]["label"] = "Hide appearance menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_appearance"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_appearance"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_appearance"]["description"] = "Switching this option on will hide the WordPress admin Appearance Menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_plugins"]["label"] = "Hide plugins menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_plugins"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_plugins"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_plugins"]["description"] = "Switching this option on will hide the WordPress admin Plugins Menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_users"]["label"] = "Hide users menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_users"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_users"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_users"]["description"] = "Switching this option on will hide the WordPress admin Users Menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_tools"]["label"] = "Hide tools menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_tools"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_tools"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_tools"]["description"] = "Switching this option on will hide the WordPress admin Tools Menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_settings"]["label"] = "Hide settings menu";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_settings"]["type"] = "check";
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_settings"]["default"] = false;
	$lime->options["wpcms"]["Admin Menu Options"]["lime_wp_hide_menu_settings"]["description"] = "Switching this option on will hide the WordPress admin Settings Menu";
}
function lime_wpcms_pre_option_update_core(){
	if(get_option( 'lime_wp_hide_menu_update' )){
		return NULL;
	}
}
function lime_wpcms_init(){
	if(get_option( 'lime_wp_hide_menu_update' )){
		remove_action('init', 'wp_version_check');
		remove_action( 'wp_version_check', 'wp_version_check' );
		remove_action( 'admin_init', '_maybe_update_core' );
		add_filter( 'pre_transient_update_core', create_function( '$a', "return null;" ) );
		add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
	}
}
function lime_wpcms_admin_menu($lime){
	if(get_option( 'lime_wp_hide_menu_update' )){
		remove_submenu_page("index.php", "update-core.php");
	}
	if(get_option( 'lime_wp_hide_menu_posts' )){
		remove_menu_page('edit.php');
	}
	if(get_option( 'lime_wp_hide_menu_media' )){
		remove_menu_page('upload.php');
	}
	if(get_option( 'lime_wp_hide_menu_links' )){
		remove_menu_page('link-manager.php');
	}
	if(get_option( 'lime_wp_hide_menu_pages' )){
		remove_menu_page('edit.php?post_type=page');
	}
	if(get_option( 'lime_wp_hide_menu_comments' )){
		remove_menu_page('edit-comments.php');
	}
	if(get_option( 'lime_wp_hide_menu_appearance' )){
		remove_menu_page('themes.php');
	}
	if(get_option( 'lime_wp_hide_menu_plugins' )){
		remove_menu_page('plugins.php');
	}
	if(get_option( 'lime_wp_hide_menu_users' )){
		remove_menu_page('users.php');
	}
	if(get_option( 'lime_wp_hide_menu_tools' )){
		remove_menu_page('tools.php');
	}
	if(get_option( 'lime_wp_hide_menu_settings' )){
		remove_menu_page('options-general.php');
	}
}
?>