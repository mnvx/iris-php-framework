<?php namespace IrisPHPFramework; ?>

	<h2><?php echo _('Options'); ?></h2>
	
    <?php
      $have_params_for_display = false;
      foreach($Config::$url_prefix_format as $format_value) { 
        if ($format_value['display']) {
          $have_params_for_display = true;
          break;
        }
      } 
    ?>

    <?php if ($have_params_for_display) { ?>
    <div class="menu">
      <div data-role="fieldcontain">
        <?php foreach($Config::$url_prefix_format as $format_name => $format_value) { ?>
          <?php if ($format_value['display'] && count($format_value['supported']) > 1) { ?>
            <h3><?php echo _($format_value['name']); ?></h3>
            <fieldset data-role="controlgroup">
              <?php foreach ($format_value['supported'] as $supported_name => $supported_value) { ?>
              <input type="radio" class="select-prefix" name="<?php echo $format_name; ?>" id="<?php echo $format_name.'_'.$supported_name; ?>" value="<?php echo $Router->prefix_url(array($format_name => $supported_name), true); ?>" <?php if ($supported_name == $Router->get_url_prefix_param_value($format_name)) { ?> checked="checked" <?php } ?>/>
              <label for="<?php echo $format_name.'_'.$supported_name; ?>"><?php echo _($supported_value); ?></label>
              <?php } ?>
            </fieldset>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
    <?php } ?>