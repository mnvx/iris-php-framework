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
* core - core files, please, do not edit them in your project, later you can update this folder, when new version of framework will be released
* data - data files (database for sqlite and other possible files)
* project - your project files, you can edit this files as you need
  - model - files for work with data
  - controller - files for processing of user actions
  - view - templates for pages
* locale - translations, you can edit whem with Poedit editor, [more info about translations](http://php.net/manual/en/book.gettext.php "PHP gettext")
* plugins - plugins
* static - css, js files, images and other static information
* temp - temp files

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