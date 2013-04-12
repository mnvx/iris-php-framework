# Iris PHP Framework

Fast MVC framework with transparent architecture and adaptive desigh for programmers, who want to create their web project rapidly and cleanly for both desktop and mobile platforms

## Advantages
* MVC
* Multilingual
* Adaptive design
* Desktop and mobile version
* Caching
* High performance
* Friendly URLs
* Module architecture
* Easy to use
* Easy to update

## Screenshots
Desktop version:  
![Iris PHP Framework](http://storage8.static.itmages.ru/i/13/0112/h_1357958773_4621523_2994f5dd48.png "Desktop version")

Mobile version:  
![Iris PHP Framework](http://storage9.static.itmages.ru/i/13/0112/h_1357958799_9080468_e61ac78baf.png "Mobile version")

## Requirements
* PHP 5.4+
* php_pdo extension (default - sqlite)
* mod_rewrite Apache module

## Catalog structure
* cache - cached pages
* core - core files, please, do not edit them in your project, later you can replace this folder, when new version of framework will be released
* data - data files (database for sqlite and other possible files)
* locale - translations, you can edit whem with Poedit editor, [more info about translations](http://php.net/manual/en/book.gettext.php "PHP gettext")
* module - custom and system modules
  - module_path - your project files, you can edit this files as you need
    + model - files for work with data
    + controller - files for processing of user actions
    + view - templates for pages
* static - css, js files, images and other static information
* temp - temp files
* theme - templates, page design

## Installation
1. Copy files into your web files folder 
2. Set write access for "cache" and "data" folders
3. Edit file `project/config.php` (set value of `Config::$base_url`)
4. Installation completed

## First step: How to add your controller
1. Edit routes: `Config::$routes` (see standart routes for example), add needed route
2. Add controller into `project/controller`
3. Add view into `project/view`
4. If necessery, add model into `project/model`

## License
[MIT](http://opensource.org/licenses/mit-license.php)

The MIT License (MIT)
Copyright (c) 2012 mnv

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## Involvement
Welcome: [Github](https://github.com/iriscrm/iris-php-framework "Iris PHP Framework on GitHub")

# Example

## Creation of new page with new controller, view, model, query and link in main menu (common case)

### Add new router

Add new element into `Config::routes` (file `project\config.php`)

    'orders' => array(
      'pattern' =>'/orders', 
      'controller' => 'order', 
      'action' => 'index',
    ),

### Add new controller

Create file `project\controller\ordercontroller.php`

    <?php
    namespace IrisPHPFramework;
    /**
     * Order Controller
     */

    class OrderController extends Controller {
      /**
       * Order list page.
       */
      function indexAction() {
        $this->login_required();

        $class_view = get_final_class_name('View');
        $view = $class_view::singleton();
        $view->assign('order_list', OrderModel::singleton()->get_list(UserModel::singleton()->login));
        $view->set_title(_('My orders'));
        $view->render("order", "orders");
      }
    }
    ?>

### Add new model

Create file `project\model\order.php`

    <?php
    namespace IrisPHPFramework;
    /**
     * Order Model
     */

    class OrderModel {
      use Singleton;
      protected $msg;
      function get_list($client_number)
      {
        $db = DB::singleton();
        $query = $db->get_client_order_list('db', $client_number);
        if (!$query) {
          $this->msg = $db->get_msg();
          return false;
        }
        // Fetch all results and process the data if the row exists.
        $results = $query->fetchAll();
        return $results;
      }
    }
    ?>

Edit `project\index.php`
    ...
    require_once(Config::project_path().'/model/order.php');
    ...

### Add new database query

Add new method into `DB` class (file `project\db.php`)

    public function get_client_order_list($db_name, $client) {
      $sql = "
        select '11227' as docnum, '2013-01-15' as docdate, 1200 as amount, 'Oil change' as claim
        union
        select '11122' as docnum, '2013-01-13' as docdate, 3120 as amount, null as claim
        union
        select '11023' as docnum, '2013-01-12' as docdate, 120.45 as amount, null as claim
      ";
      return $this->run_query($sql, array(), $db_name);
    }

### Add new view

Create file `project\view\order\orders.html.php`

    <?php namespace IrisPHPFramework; ?>
    <?php 
      $profile = $user->get_profile_info(); 
      $orders = $view->get('order_list');
    ?>

    <h2><?php echo _('My orders'); ?></h2>
    
    <div class="orders">

      <table class="table">
      <tr>
        <th><?php echo _('Order number'); ?></th>
        <th><?php echo _('Date'); ?></th>
        <th><?php echo _('Amount'); ?></th>
        <th><?php echo _('Claim'); ?></th>
      </tr>
      
      <?php foreach ($orders as $order_info) { ?>
        <tr>
          <td><?php echo $view->escape($order_info['docnum']); ?></td>
          <td><?php echo $view->escape($order_info['docdate']); ?></td>
          <td><?php echo $view->escape($order_info['amount']); ?></td>
          <td><?php echo $view->escape($order_info['claim']); ?></td>
        </tr>
      <?php } ?>
      </table>

    </div>

### Add link for this page in menu

Edit files `project\view\site\home-d.html.php` and `project\view\site\home-m.html.php`

    ...
    <li><a href="<?php echo $router->prefix_url(); ?>/orders"><?php echo _('My orders'); ?></a></li>
    ...
