<?php namespace IrisPHPFramework; ?>

	<h2>Your Profile</h2>
	
	<div class="profile">
		<p class='field'>
			<label><?php echo _('User'); ?>:</label>
			<span><?php echo $view->escape($view->get('user_name')); ?></span>
		</p>
		<p class='field'>
			<label><?php echo _('E-mail'); ?>:</label>
			<span><?php echo $view->escape($view->get('user_login')); ?></span>
		</p>
	</div>
	<a href="<?php echo $router->url('profile_edit'); ?>" data-icon="gear" data-role="button"><?php echo _('Update Information'); ?></a>