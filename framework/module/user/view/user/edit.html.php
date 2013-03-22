<?php namespace IrisPHPFramework; ?>

	<h2>Update Contact Info</h2>
	<p><span class="label label-info">Note:</span> Only enter your password if you wish to change it, otherwise leave it blank.</p>
	<form action='<?php echo $Router->url('profile_edit'); ?>' method='post' class='register-form'>
		<p>
			<label for='name'><?php echo _('User'); ?>:</label>
			<input type='text' name='name' class='text' id='name' value='<?php echo $this->escape($this->get('user_name')); ?>' />
		</p>
		<p>
			<label for='login'><?php echo _('E-mail'); ?>:</label>
			<input type='text' name='login' class='text' id='login' value='<?php echo $this->escape($this->get('user_login')); ?>' />
		</p>
		<p>
			<label for='password'>Password:</label>
			<input type='password' name='password' class='text' id='password' />
		</p>
		<p>
			<label for='password2'>Confirm Password:</label>
			<input type='password' name='password2' class='text' id='password2' />
		</p>
		<p>
			<label></label>
			<input type='submit' class='button contact' value='Update' />
		</p>
	</form>