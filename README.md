Framework
============

Fast MVC framework with adaptive desigh

Advantages
------------
* MVC
* Multilingual
* Adaptive design
* Desktop and mobile version
* Caching
* High performance
* Friendly URLs
* Easy to use
* Easy to update

Catalog structure
------------
* cache - cached pages
* core - core files, please, do not edit them in your project, later you can update this folder, when new version of framework will be released
* data - data files (database for sqlite and other possible files)
* project - your project files, you can edit this files as you need
  - models - files for work with data
  - controllers - files for processing of user actions
  - views - templates for pages
* locale - translations, you can edit whem with Poedit editor, [more info about translations](http://php.net/manual/en/book.gettext.php "PHP gettext")
* plugins - plugins
* static - css, js files, images and other static information
* temp - temp files

First steps:
------------
1. Installation
 - Copy files into your web files folder 
 - Set write access for "cache" and "data" folders
 - Edit file `project/config.php` (set value of `Config::$base_url`)
 - Installation completed
2. Add you controller. 
 - Edit routes: `Config::$routes` (see standart routes for example), add needed route
 - Add controller into `project/controllers`
 - Add view into `project/views`
 - If necessery, add model into `project/models`

License
------------
[MIT](http://opensource.org/licenses/mit-license.php)

The MIT License (MIT)
Copyright (c) 2012 mnv

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
