<?php namespace IrisPHPFramework; ?>

	<h2><?php echo _('User Profile'); ?></h2>
	
	<div class="profile">
		<p class='field'>
			<label><?php echo _('User'); ?>:</label>
			<span><big><big><?php echo $this->escape($this->get('user_name')); ?></big></big></span>
		</p>
		<p class='field'>
			<label><?php echo _('E-mail'); ?>:</label>
			<span><big><big><?php echo $this->escape($this->get('user_login')); ?></big></big></span>
		</p>
	</div>
