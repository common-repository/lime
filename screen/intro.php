<?php include "screenHeader.php"; ?>
<?php global $Lime; ?>
<div style="float:right;">
  <table>
    <tr>
      <td><a href="http://twitter.com/share" data-url="http://studio.bloafer.com/wordpress-plugins/weird-lime/" data-count="vertical" data-via="Bloafer" data-text="Lime is a wierd name for a WordPress plugin, but this plugin is cool. http://studio.bloafer.com/wordpress-plugins/weird-lime/" data-counturl="http://studio.bloafer.com/wordpress-plugins/weird-lime/" class="twitter-share-button">Tweet</a></td>
      <td><g:plusone size="tall" count="true" href="http://studio.bloafer.com/wordpress-plugins/weird-lime/"></g:plusone></td>
      <td><fb:like href="http://studio.bloafer.com/wordpress-plugins/weird-lime/" layout="box_count"></fb:like></td>
    </tr>
  </table>
  <div id="fb-root"></div>
</div>
  <div class="icon32" id="icon-lime"><img src="<?php print plugins_url('lime/img/lime-32.png') ?>" /></div><h2>Lime (v<?php print $Lime->version ?>)</h2>
  <p>Lime is a wierd name for a plugin, but this plugin is no average plugin. Below you can see<?php if(get_option( 'lime_show_news' )){ ?> the current news for Lime followed by<?php } ?> currently active modules</p>
<?php if(get_option( 'lime_show_news' )){ ?>
        <div class="limeIntroModule">
        <h2>Current News</h2>
  <div id="limeAjaxNews"></div>
  </div>
  <script>
jQuery(document).ready(function(){
	jQuery.get("<?php print plugins_url('lime/screen/news.php') ?>", function(data){
		jQuery("#limeAjaxNews").hide();
		if(data!=""){
			jQuery("#limeAjaxNews").html(data).slideDown('slow');
		}
	});
});
  </script>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	var stscr = document.createElement('script'); stscr.type = 'text/javascript'; stscr.async = 'true';
  })();
</script>
  <?php } ?>
  <?php
	foreach($Lime->core as $core_name=>$core_data){
		if($core_data["active"]){
		?>
        <div class="limeIntroModule">
        <h2><a href="admin.php?page=lime_core_<?php print $core_name ?>"><?php print $core_data["name"] ?></a></h2>
        <?php
        if($core_data["description"]){
			?>
            <p><?php print $core_data["description"] ?></p>
			<?php
		}
		?>
        </div>
		<?php
		}
	}
	?>

<?php include "screenFooter.php"; ?>
