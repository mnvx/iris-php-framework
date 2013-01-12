<?php namespace IrisPHPFramework; ?>

	<h2>Your Profile</h2>
	
	<div class="profile">
		<p class='field'>
			<label>Name:</label>
			<span><big><big><?php echo $template->get('user_name'); ?></big></buserig></span>
		</p>
		<p class='field'>
			<label>E-mail:</label>
			<span><big><big><?php echo $template->get('user_login'); ?></big></big></span>
		</p>
	</div>
	<a href="<?php echo $router->_url(); ?>/user/edit"><?php echo _('Update Information'); ?></a>