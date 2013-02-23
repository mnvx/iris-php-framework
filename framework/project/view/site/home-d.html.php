<?php namespace IrisPHPFramework; ?>
<div id="myCarousel" class="carousel slide hidden-phone">
  <div class="carousel-inner">
    <div class="active item">
      <img src="<?php echo Config::$base_url; ?>/static/images/1.jpg"/>
      <div class="carousel-caption">
        <h4>MVC</h4>
        <p>This framework is based on MVC structure that allows you to clearly separate templates, actions, and work with data.</p>
      </div>
    </div>
    <div class="item">
      <img src="<?php echo Config::$base_url; ?>/static/images/2.jpg"/>
      <div class="carousel-caption">
        <h4>Adaptive design</h4>
        <p>Desktop version based on bootstrap. Mobile version based on JQuery. You can specify templates for other devices.</p>
      </div>
    </div>
    <div class="item">
      <img src="<?php echo Config::$base_url; ?>/static/images/3.jpg"/>
      <div class="carousel-caption">
        <h4>High performance</h4>
        <p>Carefully written code works fast. Good architecture allows you to write Your applications quickly.</p>
      </div>
    </div>
  </div>
  <!-- Carousel nav -->
  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
</div>

<div>
  <?php if ($user->is_logged()) { ?>
  <div class="menu">
    <ul class="nav nav-pills nav-stacked">
      <li><a href="<?php echo $router->url('profile'); ?>"><?php echo _('Profile'); ?></a></li>
      <li><a href="<?php echo $router->url('logout'); ?>"><?php echo _('Exit'); ?></a></li>
    </ul>
  </div>
  <?php } else { ?>
  <h3><a href="<?php echo $router->url('login'); ?>"><?php echo _('Login'); ?></a>
  <small><?php echo _('If you are already registered, sign in with your username and password'); ?>.</small></h3>
  <h3><a href="<?php echo $router->url('signup'); ?>"><?php echo _('Register'); ?></a>
  <small><?php echo _('If you do not have an account, please register'); ?>.</small></h3>
  <?php } ?>
</div>
