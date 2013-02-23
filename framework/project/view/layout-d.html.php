<?php namespace IrisPHPFramework; ?>
<!DOCTYPE html>
<html lang="<?php echo $router->get_url_prefix_param_value('locale'); ?>">
<head>
  <title><?php echo $view->escape($view->page_title()); ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <link rel="stylesheet" href="<?php echo Config::$base_url; ?>/static/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?php echo Config::$base_url; ?>/static/css/main-d.css" type="text/css" media="screen" />
  <link rel="shortcut icon" href="<?php echo Config::$base_url; ?>/static/images/favicon.ico" /> 
  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?php echo _(Config::$app_description); ?>" /> 
  <meta name="keywords" content="<?php echo _(Config::$app_keywords); ?>" /> 
</head>

<body>
  <div id="wrap">
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="<?php echo $router->prefix_url(); ?>"><?php echo _(Config::$app_name); ?></a>
          <div class="nav-collapse collapse">
            <?php include $view->get_view_file_name('params'); ?>
            <ul class="nav">
              <li class="divider-vertical"></li>
              <li><p class="navbar-text">
              <?php if ($user->is_logged()) { ?>
                <?php echo _('Logged in as'); ?> <a href="<?php echo $router->url('profile'); ?>" class="navbar-link"><?php echo $view->escape($user->get_name()); ?></a>
                / <a href="<?php echo $router->url('logout'); ?>" class="navbar-link"><?php echo _('Logout'); ?></a>
              <?php } else { ?>
                <a href="<?php echo $router->url('login'); ?>" class="navbar-link"><?php echo _('Login'); ?></a>
                <?php echo _('or'); ?>
                <a href="<?php echo $router->url('signup'); ?>" class="navbar-link"><?php echo _('Signup'); ?></a>
              <?php } ?>
              </p></li>
            </ul>
            <?php if (Config::$debug) { ?>
            <ul class="nav">
              <li class="divider-vertical"></li>
              <li><a href="#debug" role="button" class="navbar-link" data-toggle="modal"><?php echo _('Debug'); ?></a></li>
            </ul>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>

    <div class="container" id="main">
      <?php if (!($router->get_controller_name() == 'site' && $router->get_action_name() == 'home')) { ?>
      <div class="hero-unit">
        <h1><?php echo _(Config::$app_name); ?> <small>desktop mode</small></h1>
      </div>
      <?php } else { ?>
      <div class="main page-header">
        <h1><?php echo _(Config::$app_name); ?> <small>desktop mode</small></h1>
      </div>
      <?php } ?>

      <div>
        <?php echo $view->get_msg(); ?>
      </div>

      <?php if ($view->get_inner_file_name()) { ?>
      <div class="controller <?php echo $router->get_controller_name(); ?>">
        <?php include $view->get_inner_file_name(); ?>
      </div>
      <?php } ?>

    </div>
  </div>

  <div id="footer">
    <div class="container navbar navbar-inverse">
      <p class="navbar-text pull-left">&copy; 2012 <?php echo _(Config::$app_name); ?></p>
      <ul class="pull-right nav nav-pills">
        <li><a href="<?php echo $router->url('about'); ?>"><?php echo _('About'); ?></a></li>
        <li><a href="<?php echo $router->url('terms'); ?>"><?php echo _('Terms of Service'); ?></a></li>
      </ul>
    </div>
  </div>

  <script src="<?php echo Config::$base_url; ?>/static/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo Config::$base_url; ?>/static/bootstrap/js/bootstrap.min.js"></script>
  
  <?php include $view->get_view_file_name('debug'); ?>
  
</body>
</html>