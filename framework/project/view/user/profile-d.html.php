<?php namespace IrisPHPFramework; ?>

	<h2>Your Profile</h2>
	
	<div class="profile">
		<p class='field'>
			<label><?php echo _('User'); ?>:</label>
			<span><big><big><?php echo $view->escape($view->get('user_name')); ?></big></big></span>
		</p>
		<p class='field'>
			<label><?php echo _('E-mail'); ?>:</label>
			<span><big><big><?php echo $view->escape($view->get('user_login')); ?></big></big></span>
		</p>
	</div>
	<a href="<?php echo $router->url('profile_edit'); ?>"><?php echo _('Update Information'); ?></a>