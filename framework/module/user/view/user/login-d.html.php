<?php namespace IrisPHPFramework; ?>

  <h2><?php echo _('Login'); ?></h2>

  <p><?php echo _('Please login to view your order status or support requests'); ?>.</p>
  
  <form method="post" action="<?php echo $Router->url('login'); ?>">
    <div data-role="fieldcontain">
      <label for="login" class="ui-input-text"><?php echo _('E-mail Address'); ?>:</label>
      <input type="text" name="login" class="text" id="login" value="" />
    </div>
    <div data-role="fieldcontain">
      <label for="password"><?php echo _('Password'); ?>:</label>
      <input type="password" name="password" class="text" id="password" />
    </div>
    <div data-role="fieldcontain">
      <input type="hidden" name="task" value="login" />
      <input type="hidden" name="redirect_to" value="" />
      <input type="submit" data-icon="check" class="button submit" value="<?php echo _('Login'); ?>">
    </div>
  </form>