<?php namespace IrisPHPFramework; ?>
<div class="menu">
    <ul data-role="listview" data-inset="true" data-theme="f">
      <li data-role="list-divider"><?php echo _('User'); ?></li>
      <?php if($user->is_logged()) { ?>
      <li><a href="<?php echo $router->url('profile'); ?>"><?php echo _('Profile'); ?></a></li>
      <li><a href="<?php echo $router->url('logout'); ?>"><?php echo _('Exit'); ?></a></li>
      <?php }else{ ?>
      <li><a href="<?php echo $router->url('signup'); ?>"><?php echo _('Register'); ?></a></li>
      <li><a href="<?php echo $router->url('login'); ?>"><?php echo _('Login'); ?></a></li>
      <?php } ?>
      <li data-role="list-divider"><?php echo _('Pages'); ?></li>
      <li><a href="<?php echo $router->url('about'); ?>"><?php echo _('About'); ?></a></li>
      <li><a href="<?php echo $router->url('terms'); ?>"><?php echo _('Terms of Service'); ?></a></li>
    </ul>
</div>
