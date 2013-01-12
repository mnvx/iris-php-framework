<?php namespace IrisPHPFramework; ?>
    <?php
      $have_params_for_display = false;
      foreach(Config::$url_prefix_format as $format_value) { 
        if ($format_value['display']) {
          $have_params_for_display = true;
          break;
        }
      } 
    ?>
    <?php if ($have_params_for_display) { ?>
      <fieldset data-role="controlgroup" data-type="horizontal" class="settings-header">
        <?php foreach(Config::$url_prefix_format as $format_name => $format_value) { ?>
          <?php if ($format_value['display'] && count($format_value['supported']) > 1) { ?>
            <select name="select-prefix-<?php echo $format_name; ?>" id="select-prefix-<?php echo $format_name; ?>" data-theme="c" data-icon="gear" data-inline="true" class="select-prefix">
              <?php foreach ($format_value['supported'] as $supported_name => $supported_value) { ?>
              <option value="<?php echo $router->_url($format_name, $supported_name, true); ?>" <?php echo $router->get_url_prefix($format_name) == $supported_name ? 'selected' : ''; ?>><?php echo _($supported_value); ?></option>
              <?php } ?>
            </select>
          <?php } ?>
        <?php } ?>
      </fieldset>
    <?php } ?>