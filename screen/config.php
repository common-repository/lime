<?php include "screenHeader.php"; ?>
<?php
if(count($_POST)>=1){
	if( wp_verify_nonce($_POST[$this->nonceField],"lime_" . $core_name)){
		foreach($this->options[$core_name] as $title=>$options){
			foreach($options as $key=>$data){
				update_option($key, $_POST[$key]);
			}
		}
		print $this->lime_message($currentCore["name"] . " options have been updated, some option may require a refresh to become visible.");
	}else{
		print $this->lime_message("Nonce Failed", "error");
	}
}
?>
  <div class="icon32" id="icon-lime"><img src="<?php print plugins_url('lime/img/lime-32.png') ?>" /></div><h2><a href="admin.php?page=lime/screen/intro.php">Lime</a> &raquo; <?php print $currentCore["name"];?></h2>
  <?php if($currentCore["active"]){ ?>
<?php $this->lime_run_function($core_name . "_settings_header"); ?>
  <?php
  if(isset($this->options[$core_name])){
	  ?>
	      <form method="post" action="">
      <?php wp_nonce_field("lime_" . $core_name, $this->nonceField); ?>
<?php
	  foreach($this->options[$core_name] as $title=>$options){
		  ?>
      <table class="form-table">
        <tbody>
          <tr valign="top">
            <td colspan="2"><h3 class="limeh3"><img src="<?php print plugins_url('lime/img/lime-16.png') ?>" align="absmiddle" /> <?php print $title ?></h3></td>
          </tr>
           <?php
			foreach($options as $key=>$data){
				include "fields.php";
			}
			?>
		  </tbody>
      </table>
      <?php
		}
	  ?>
    <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
    </form>
	  <?php
  }
  ?>
<?php $this->lime_run_function($core_name . "_settings_footer"); ?>
  <?php }else{ ?>
  <?php print $this->lime_message($currentCore["name"] . ' is currently inactive, please visit the settings page to <a href="admin.php?page=lime/screen/settings.php">activate ' . $currentCore["name"] . '</a>', "error") ?></p>
  <?php } ?>
<?php include "screenFooter.php"; ?>

