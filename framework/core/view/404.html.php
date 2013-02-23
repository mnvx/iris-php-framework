<?php namespace IrisPHPFramework; ?>
<?php
  $class_config = get_final_class_name('Config');
?>
<!DOCTYPE html>
<html lang="<?php echo $router->get_url_prefix_param_value('locale'); ?>">
<head>
  <title><?php echo $view->escape($view->page_title()); ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?php echo _($class_config::$app_description); ?>" /> 
  <meta name="keywords" content="<?php echo _($class_config::$app_keywords); ?>" /> 
</head>

<body>

  <h1><?php echo _($class_config::$app_name); ?></h1>
  <p>Page not found</p>
  <?php include $view->get_view_file_name('debug'); ?>
  
</body>
</html>