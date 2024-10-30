<?php
function lime_jquery_title(){
	return "jQuery Toolkit";
}
function lime_jquery_description(){
	return "This core capability allows you to upgrade your jQuery and add cool stuff like UI";
}
function lime_jquery_enqueue_scripts(){
	//This will replace the working copy of jQuery
	if(get_option( 'lime_jquery_enabled' )){
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/' . get_option( 'lime_jquery_version' ) . '/jquery.min.js');
		wp_enqueue_script( 'jquery' );
	}
	if(get_option( 'lime_jqueryui_enabled' )){
		wp_deregister_script( 'jquery-ui-core' );
		wp_deregister_script( 'jquery-ui-tabs' );
		wp_deregister_script( 'jquery-ui-sortable' );
		wp_deregister_script( 'jquery-ui-draggable' );
		wp_deregister_script( 'jquery-ui-droppable' );
		wp_deregister_script( 'jquery-ui-selectable' );
		wp_deregister_script( 'jquery-ui-resizable' );
		wp_deregister_script( 'jquery-ui-dialog' );
		wp_register_script( 'jquery-ui-core', 'https://ajax.googleapis.com/ajax/libs/jqueryui/' . get_option( 'lime_jqueryui_version' ) . '/jquery-ui.min.js');
		wp_enqueue_script( 'jquery-ui-core' );
	}
}
function lime_jquery_options($lime){
	$lime->options["jquery"]["jQuery Core"]["lime_jquery_enabled"]["label"]			= "Enable CDN based jQuery";
	$lime->options["jquery"]["jQuery Core"]["lime_jquery_enabled"]["default"]		= false;
	$lime->options["jquery"]["jQuery Core"]["lime_jquery_enabled"]["type"]			= "check";

	$lime->options["jquery"]["jQuery Core"]["lime_jquery_version"]["label"]			= "jQuery core version";
	$lime->options["jquery"]["jQuery Core"]["lime_jquery_version"]["default"]		= "1.6";
	$lime->options["jquery"]["jQuery Core"]["lime_jquery_version"]["type"]			= "drop";
	$lime->options["jquery"]["jQuery Core"]["lime_jquery_version"]["data"]["1"]		= '1 - This loads the latest stable version';
	$lime->options["jquery"]["jQuery Core"]["lime_jquery_version"]["data"]["1.6"]	= '1.6';
	$lime->options["jquery"]["jQuery Core"]["lime_jquery_version"]["data"]["1.6.3"]	= "1.6.3";

	$lime->options["jquery"]["jQuery UI"]["lime_jqueryui_enabled"]["label"]			= "Enable CDN based jQuery UI";
	$lime->options["jquery"]["jQuery UI"]["lime_jqueryui_enabled"]["default"]		= false;
	$lime->options["jquery"]["jQuery UI"]["lime_jqueryui_enabled"]["type"]			= "check";

	$lime->options["jquery"]["jQuery UI"]["lime_jqueryui_version"]["label"]			= "jQuery UI core version";
	$lime->options["jquery"]["jQuery UI"]["lime_jqueryui_version"]["default"]		= "1.8";
	$lime->options["jquery"]["jQuery UI"]["lime_jqueryui_version"]["type"]			= "drop";
	
	$lime->options["jquery"]["jQuery UI"]["lime_jqueryui_version"]["data"]["1"]	= '1 - This loads the latest stable version';
	$lime->options["jquery"]["jQuery UI"]["lime_jqueryui_version"]["data"]["1.8"]	= '1.8';
	$lime->options["jquery"]["jQuery UI"]["lime_jqueryui_version"]["data"]["1.8.16"]= "1.8.16";

}
?>