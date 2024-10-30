<?php
/*

WordPress Information:
==============================================================================
Plugin Name: Lime
Plugin URI: http://studio.bloafer.com/wordpress-plugins/weird-lime/
Description: Lime is a wierd name for a plugin, but this plugin is no average plugin.
Version: 0.1.1
Author: Kerry James
Author URI: http://studio.bloafer.com/
Text Domain: lime
Domain Path: /lang/
 
*/
//if(!current_user_can('manage_options')){
//	wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) );
//}

if ( !class_exists( 'Lime' ) ) {
	class Lime{
		var $version = "0.1.1";
		var $core = array();
		var $core_root = false;
		var $lime_log = array();
		var $lime_log_active = true;
		var $options = array();
		var $nonceField = "";
		var $last_message = "";
		var $news = false;
		function Lime(){
			//Set nonce field
			$this->nonceField = md5(__CLASS__ . $this->version);
			//Set core root
			if(!$this->core_root){
				$this->core_root = rtrim(rtrim(dirname(__FILE__),'/'),'\\').'/';
			}
			// Load core files
			$this->lime_load_core();
			// Init core files if required
			$this->lime_scan_functions("lime_init");
			// Load lime options
			$this->lime_load_options();
			// Load required scripts
			add_action('wp_enqueue_scripts', array( &$this, 'hook_wp_enqueue_scripts'));
			// Set 404 interceptors
			add_filter( 'status_header', array( &$this, 'hook_status_header' ), 1, 2 );
			// Set init interceptors
			add_action('init', array(&$this, 'hook_init'));
//			add_action( 'admin_init', array( &$this, 'admin_init' ) );

            add_action( 'admin_menu', array(&$this, 'hook_admin_menu'));
			// Set head interceptors
			add_action( 'wp_head', array( &$this, 'hook_wp_head' ) );
			// Set footer interceptors
			add_action( 'wp_footer', array( &$this, 'hook_wp_footer' ) );
			// Set permalink interceptors
			add_filter( 'the_permalink_rss', array( &$this, 'hook_the_permalink_rss' ) );
			// Set content interceptors
			add_filter( 'the_content', array( &$this, 'hook_the_content' ) );
			// Set exerpt interceptors
			add_filter( 'the_excerpt', array( &$this, 'hook_the_excerpt' ) );
			// Set comment interceptors
			add_filter( 'comment_text', array( &$this, 'hook_comment_text' ) );
			// Set update core interceptors
			add_filter('pre_option_update_core', array(&$this, 'hook_pre_option_update_core'));
		}
		private function lime_load_core(){
			$coreBase = $this->core_root . 'core/';
			if (is_dir($coreBase)) {
				if ($coreBaseHandle = opendir($coreBase)) {
					while (($coreBaseFile = readdir($coreBaseHandle)) !== false) {
						if($coreBaseFile!="." && $coreBaseFile!=".." && is_file($coreBase . $coreBaseFile)){
							include_once $coreBase . $coreBaseFile;
							$rootName = $coreName = basename($coreBase . $coreBaseFile, ".php");
							$coreDescription = false;
							$this->core[$rootName]['active'] = (get_option("lime_active_" . $rootName, false)=="on")?true:false;
							$this->core[$rootName]['settings'] = true;
							$limeFunctionTest = 'lime_' . $rootName . '_title';
							if(function_exists($limeFunctionTest)){
								eval("$" . "coreName = " . $limeFunctionTest . "();");
							}
							$limeFunctionTest = 'lime_' . $rootName . '_description';
							if(function_exists($limeFunctionTest)){
								eval("$" . "coreDescription = " . $limeFunctionTest . "();");
							}
							$this->core[$rootName]['name'] = $coreName;
							$this->core[$rootName]['description'] = $coreDescription;
						}
					}
					closedir($coreBaseHandle);
				}
			}
			ksort($this->core[$rootName]);
		}
		function hook_admin_menu(){
			$showInactive = (get_option("lime_show_inactive_modules", false)=="on")?true:false;
			if(current_user_can('manage_options')){
				add_menu_page('Lime', 'Lime', 10, 'lime/screen/intro.php', '', plugins_url('lime/img/lime-16.png'), 3);
				foreach($this->core as $core_name=>$core_data){
					if($core_data["settings"]){
						if($core_data["active"]){
							add_submenu_page('lime/screen/intro.php', $core_data["name"], $core_data["name"], 'manage_options', 'lime_core_' . $core_name, array(&$this, 'admin_core_menu'));
						}else{
							if($showInactive){
								add_submenu_page('lime/screen/intro.php', "<strike>" . $core_data["name"] . "</strike>", "<strike>" . $core_data["name"] . "</strike>", 'manage_options', 'lime_core_' . $core_name, array(&$this, 'admin_core_menu'));
							}
						}
					}
				}
				add_submenu_page('lime/screen/intro.php', 'Lime Settings', 'Lime Settings', 'manage_options', 'lime/screen/settings.php');
			}
			$this->lime_scan_functions("admin_menu");
		}
		// Module sub-hooks
		function hook_wp_enqueue_scripts(){
			wp_enqueue_script('jquery');
			$this->lime_scan_functions("enqueue_scripts");
		}
		function hook_status_header( $status_header, $header ) {
			if ( ( $header == 404 ) ) {
				$this->lime_scan_functions("404");
			}
			return $status_header;
		}
		function hook_init(){ $this->lime_scan_functions("init"); }
		function hook_wp_head(){ $this->lime_scan_functions("wp_head"); }
		function hook_wp_footer(){ $this->lime_scan_functions("wp_footer"); }
		function hook_the_permalink_rss( $pVar ){ return $this->lime_scan_functions("the_permalink_rss", $pVar); }
		function hook_the_content( $pVar ){ return $this->lime_scan_functions("the_content", $pVar); }
		function hook_the_excerpt( $pVar ){ return $this->lime_scan_functions("the_excerpt", $pVar); }
		function hook_comment_text( $pVar ){ return $this->lime_scan_functions("comment_text", $pVar); }
		function hook_pre_option_update_core(){ $this->lime_scan_functions("pre_option_update_core"); }
		
	
		function admin_core_menu($data){
			$passed_core_name = $this->lime_sanitize_alnum($_GET["page"]);
			if($passed_core_name){
				$core_sections = explode("_", $passed_core_name);
				if(count($core_sections==3)){
					$core_name = $core_sections[2];
					$currentCore = $this->core[$core_name];
					include "screen/config.php";
				}else{
					print "Core file is broken";
				}
			}
		}
		function lime_mod_rewrite(){
			$rewrite_rules = get_option("rewrite_rules");
			$this->last_message = 'This part of Lime requires non-default Permalink settings, to change this please <a href="options-permalink.php">click here</a>.';
			return $rewrite_rules;
		}
		function lime_message($text, $type="updated"){
			return '<div id="message" class="' . $type . '"><p>' . $text . '</p></div>';
		}
		private function lime_sanitize_alnum($input=false){
			return $input;
		}
		private function lime_load_options(){
			$this->lime_scan_functions("options");
			$this->options["lime_sitemap_file"] = "sitemap.xml";
			$this->options["lime_wp_root"] = false;
		}
		private function lime_save_options(){
			print json_encode($this->options);
		}
		function lime_fetch_root(){
			if( !$this->options["lime_wp_root"] ){
				$root = '/';
				if ( preg_match( '#^http://[^/]+(/.+)$#', get_option( 'siteurl' ), $matches ) ) {
					$root = $matches[1];
				}
				// Make sure it ends with slash
				$root = rtrim($root, '/').'/';
			}else{
				$root = $this->options["lime_wp_root"];
			}
			return $root;
		}
		private function log($header="basic", $content=""){
			if($this->lime_log_active){
				$log_entry["dtg"] = date("d/m/Y H:i:s");
				$log_entry["content"] = date("d/m/Y H:i:s");
				$this->lime_log[$header][] = $log_entry;
			}
		}
		private function lime_scan_functions($lime_function_name=false, $passedVar=false){
			if($lime_function_name){
				foreach($this->core as $core_name=>$core_data){
					if($core_data["active"]){
						$limeFunctionTest = $core_name . '_' . $lime_function_name;
						$passedVar = $this->lime_run_function($limeFunctionTest, $passedVar);
					}
				}
			}
			return $passedVar;
		}
		function lime_run_function($limeFunctionTest=false, $passedVar=false){
			$limeFunctionTest = 'lime_' . $limeFunctionTest;
			if($limeFunctionTest){
				if(function_exists($limeFunctionTest)){
					if($passedVar){
						eval("$" . "passedVar = " . $limeFunctionTest . "($" . "this, $" . "passedVar);");
					}else{
						eval("$" . "passedVar = " . $limeFunctionTest . "($" . "this);");
					}
				}
			}
			return $passedVar;
		}
		function lime_fetch_news(){
			if(!$this->news){
				$this->news = wp_remote_fopen('http://lime.studio.bloafer.com/');
			}
			return $this->news;
		}
	}
	global $Lime;
	$Lime = new Lime;
}

?>