<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<title><?php echo $template->page_title(); ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="<?php echo Config::$base_url; ?>/static/jquery.mobile-1.2.0/jquery.mobile-1.2.0.min.css" />
<link rel="stylesheet" href="<?php echo Config::$base_url; ?>/static/css/main-m.css" type="text/css" media="screen" />
<link rel="shortcut icon" href="<?php echo Config::$base_url; ?>/static/images/favicon.ico" /> 

<script src="<?php echo Config::$base_url; ?>/static/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo Config::$base_url; ?>/static/jquery.mobile-1.2.0/jquery.mobile-1.2.0.min.js"></script>
<script src="<?php echo Config::$base_url; ?>/static/js/functions.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?php echo _(Config::$app_description); ?>" /> 
<meta name="keywords" content="<?php echo _(Config::$app_keywords); ?>" /> 
</head>

<body>
<div data-role="page" data-theme="c" data-add-back-btn="true">
	<div data-role="header" data-theme="a">
    <a href="<?php echo $router->_url(); ?>/options" data-icon="gear" class="ui-btn-right">Options</a>
    <span class="ui-title" ><a href="<?php echo $router->_url(); ?>"><?php echo _(Config::$app_name); ?></a></span>
  </div>
    
	<div data-role="content" role="main">
		<?php echo $template->get_msg(); ?>
	</div>

  <?php if ($template->get_inner_file_name()) { ?>
  <div class="controller <?php echo $template->get_controller_name(); ?>">
    <?php include $template->get_inner_file_name(); ?>
  </div>
  <?php } ?>


</div>
</body>
</html>