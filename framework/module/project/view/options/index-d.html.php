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
      <ul class="nav nav-pills nav-stacked">
        <?php foreach($Config::$url_prefix_format as $format_name => $format_value) { ?>
          <?php if ($format_value['display'] && count($format_value['supported']) > 1) { ?>
            <li><?php echo _($format_value['name']); ?></li>
              <?php foreach ($format_value['supported'] as $supported_name => $supported_value) { ?>
              <li <?php if ($supported_name == $router->get_url_prefix_param_value($format_name)) { ?> class="active" <?php } ?>> <a  href="<?php echo $router->prefix_url(array($format_name => $supported_name), true); ?>"><?php echo _($supported_value); ?></a></li>
              <?php } ?>
          <?php } ?>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>