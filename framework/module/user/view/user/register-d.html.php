<?php namespace IrisPHPFramework; ?>

  <h2><?php echo _('Register'); ?></h2>
  <form action='<?php echo $Router->url('signup'); ?>' method='post' class='register-form'>
    <p>
      <label for='name'><?php echo _('Name'); ?>:</label>
      <input type='text' name='name' class='text' id='name' value='' />
    </p>
    <p>
      <label for='login'><?php echo _('E-mail Address'); ?>:</label>
      <input type='text' name='login' class='text' id='login' value='' />
    </p>
    <p>
      <label for='password'><?php echo _('Password'); ?>:</label>
      <input type='password' name='password' class='text' id='password' />
    </p>
    <p>
      <label for='password2'><?php echo _('Confirm Password'); ?>:</label>
      <input type='password' name='password2' class='text' id='password2' />
    </p>
    <p>
      <label></label>
      <input type='submit' data-icon='check' class='button contact' value='<?php echo _('Register'); ?>' />
    </p>
  </form>