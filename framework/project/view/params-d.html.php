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
      <ul class="nav">
        <?php foreach(Config::$url_prefix_format as $format_name => $format_value) { ?>
          <?php if ($format_value['display'] && count($format_value['supported']) > 1) { ?>
            <li class="dropdown">
              <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"><?php echo _($format_value['name']); ?></a>
              <ul class="dropdown-menu" role="menu" aria-labelledby="<?php echo $format_name; ?>">
                <?php foreach ($format_value['supported'] as $supported_name => $supported_value) { ?>
                <li><a href="<?php echo $router->prefix_url(array($format_name => $supported_name), true); ?>"><?php echo _($supported_value); ?></a></li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>
        <?php } ?>
      </ul>
    <?php } ?>