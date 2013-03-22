<?php namespace IrisPHPFramework; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<title><?php echo $this->escape($this->page_title()); ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="<?php echo $Config::$base_url; ?>/static/jquery.mobile-1.2.0/jquery.mobile-1.2.0.min.css" />
<link rel="stylesheet" href="<?php echo $Config::$base_url; ?>/theme/<?php echo basename(__DIR__); ?>/css/main-m.css" type="text/css" media="screen" />
<link rel="shortcut icon" href="<?php echo $Config::$base_url; ?>/theme/<?php echo basename(__DIR__); ?>/images/favicon.ico" /> 

<script src="<?php echo $Config::$base_url; ?>/static/jquery-1.8.3.min.js"></script>
<script src="<?php echo $Config::$base_url; ?>/static/jquery.mobile-1.2.0/jquery.mobile-1.2.0.min.js"></script>
<script src="<?php echo $Config::$base_url; ?>/module/<?php echo CoreModule::singleton()->get_class_path_name('OptionsConfig'); ?>/js/functions.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?php echo _($Config::$app_description); ?>" /> 
<meta name="keywords" content="<?php echo _($Config::$app_keywords); ?>" /> 
</head>

<body>
<div data-role="page" data-theme="c" data-add-back-btn="true">
	<div data-role="header" data-theme="a">
    <a href="<?php echo $Router->url('options'); ?>" data-icon="gear" class="ui-btn-right">Options</a>
    <span class="ui-title" ><a href="<?php echo $Router->prefix_url(); ?>"><?php echo _($Config::$app_name); ?></a></span>
  </div>
    
	<div data-role="content" role="main">
		<?php echo $this->get_msg(); ?>
	</div>

  <?php if ($this->get_inner_file_name()) { ?>
  <div class="controller <?php echo $Router->get_controller_name(); ?>">
    <?php include $this->get_inner_file_name(); ?>
  </div>
  <?php } ?>


</div>
</body>
</html>