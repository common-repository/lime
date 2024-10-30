<?php
$currentValue = get_option($key, $data["default"]);
?>
          <tr valign="top">
            <th scope="row"><label for="<?php print $key; ?>"><?php print isset($data["label"])?$data["label"]:$key; ?></label></th>
            <td>
		  <?php if($data["type"]=="text"){ ?>
            <input type="text" name="<?php print $key ?>" id="<?php print $key ?>" value="<?php echo $currentValue; ?>" class="regular-text" />
          <?php }elseif($data["type"]=="check"){ ?>
            <input type="checkbox" name="<?php print $key ?>" id="<?php print $key ?>"<?php if($currentValue){ ?> checked="checked"<?php } ?> />
          <?php }elseif($data["type"]=="textarea"){ ?>
            <textarea cols="30" rows="5" id="<?php print $key ?>" name="<?php print $key ?>"><?php echo $currentValue; ?></textarea>
          <?php }elseif($data["type"]=="drop" && isset($data["data"])){ ?>
            <select name="<?php print $key ?>" id="<?php print $key ?>">
              <?php foreach($data["data"] as $k=>$v){
				  ?>  <option value="<?php print $k ?>" <?php if($currentValue==$k){ ?> selected="selected"<?php } ?>><?php print $v ?></option><?php
			  }
			  ?>
            </select>
          <?php }elseif($data["type"]=="radio" && isset($data["data"])){ ?>
              <?php foreach($data["data"] as $k=>$v){
				  ?>
                  <div>
                  <input type="radio" name="<?php print $key ?>" id="<?php print $key ?>_<?php print $k ?>"<?php if($currentValue==$k){ ?> checked="checked"<?php } ?> value="<?php print $k ?>" />
                  <label for="<?php print $key ?>_<?php print $k ?>"><?php print $v ?></label>
                  </div>
				  <?php
			  }
			  ?>
          <?php }elseif($data["type"]=="warn"){ ?>
            &nbsp;
          <?php }else{ ?>
            Incorrect settings for field <?php print $key ?>.
          <?php } ?>
                <?php if(isset($data["description"])){ ?><span class="description"><?php print $data["description"] ?></span><?php } ?>
            </td>
          </tr>
