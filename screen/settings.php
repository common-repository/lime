<?php include "screenHeader.php"; ?>
<?php global $Lime; ?>
<?php
foreach($Lime->core as $core_name=>$core_data){
	$options["lime_active_" . $core_name]["label"] = $core_data["name"] . " active";
	$options["lime_active_" . $core_name]["type"] = "check";
	$options["lime_active_" . $core_name]["default"] = false;
	$options["lime_active_" . $core_name]["description"] = "By switching on this option you will have the ability to modify " . $core_data["name"] . " options";
}
$options["lime_show_inactive_modules"]["label"] = "Display inactive modules";
$options["lime_show_inactive_modules"]["type"] = "check";
$options["lime_show_inactive_modules"]["default"] = false;
$options["lime_show_inactive_modules"]["description"] = "By switching on this option inactive modules will appear in the Lime menu system";

$options["lime_show_news"]["label"] = "Display news on Lime homepage";
$options["lime_show_news"]["type"] = "check";
$options["lime_show_news"]["default"] = true;
$options["lime_show_news"]["description"] = "By switching off this option Lime will not display news on its homepage";

if(count($_POST)>=1){
	if( wp_verify_nonce($_POST[$Lime->nonceField],'lime_settings')){
		foreach($options as $editField=>$editValue){
			update_option($editField, $_POST[$editField]);
		}
		print $Lime->lime_message("Sub module options have been updated");
	}else{
		print $Lime->lime_message("Nonce Failed", "error");
	}
}

?>
  <div class="icon32" id="icon-lime"><img src="<?php print plugins_url('lime/img/lime-32.png') ?>" /></div><h2><a href="admin.php?page=lime/screen/intro.php">Lime</a> &raquo; Settings</h2>
  <p>On this screen you can switch off any sub-modules of Lime, by default all sub-modules are switched off</p>
    <form method="post" action="">
      <?php wp_nonce_field("lime_settings", $Lime->nonceField); ?>
  <?php
		  ?>
      <table class="form-table">
        <tbody>
           <?php
			foreach($options as $key=>$data){
				include "fields.php";
			}
	  ?>
        </tbody>
      </table>
    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
      </form>
<?php include "screenFooter.php"; ?>
