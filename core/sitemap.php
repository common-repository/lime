<?php
function lime_sitemap_title(){
	return "XML Sitemap";
}
function lime_sitemap_description(){
	return "XML Sitemap will be available in version 0.2, please update as soon as version 0.2 is available<br /><br />Thanks<br />The Bloafer Team";
}
function lime_sitemap_settings_header($lime){
	if(!$lime->lime_mod_rewrite()){
		print $lime->lime_message($lime->last_message, "error");
	}
	print $lime->lime_message(lime_sitemap_description());
}

function lime_sitemap_404($lime){
	if( ( $lime->options["lime_sitemap_file"] )){
		if ( $lime->lime_fetch_root().$lime->options["lime_sitemap_file"] == $_SERVER['REQUEST_URI'] ) {
			print "Nothing Here";
			exit();
		}
	}
}
?>