
	<h2>Your Profile</h2>
	
	<div class="profile">
		<p class='field'>
			<label>Name:</label>
			<span><?php echo $template->get('user_name'); ?></span>
		</p>
		<p class='field'>
			<label>E-mail:</label>
			<span><?php echo $template->get('user_login'); ?></span>
		</p>
	</div>
	<a href="<?php echo $router->_url(); ?>/user/edit" data-icon="gear" data-role="button"><?php echo _('Update Information'); ?></a>