<?php namespace IrisPHPFramework; ?>
  <?php if (Config::$debug) { ?>
    <div id="debug" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="debugLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="debugLabel"><?php echo _('Debug information'); ?></h3>
      </div>
      <div class="modal-body">
        <h4 id="debugLabel"><?php echo _('Log'); ?></h4>
        <table>
          <thead><tr><th>Time</th><th>Message</th></tr></thead>
          <tbody>
          <?php 
            $debug->log('Finish');
            $log = $debug->log_info();
            foreach($log as $value) {
              echo '<tr><td>'.$value['time'].'</td><td>'.$value['message']."</td></tr>";
            }
          ?>
          </tbody></table>
        <h4 id="debugLabel"><?php echo _('Route'); ?> <small><?php echo $view->escape($debug->route_name()); ?></small></h4>
        <pre><?php 
          $route = $debug->route_info();
          print_r($route);
        ?></pre>
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _('Close'); ?></button>
      </div>
    </div>
  <?php } ?>