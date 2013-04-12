<?php

class WebTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected $captureScreenshotOnFailure = TRUE;
  protected $rootUrl = 'http://localhost/framework';
  protected $screenshotsPathName = 'frameworkErrorScreens';
  protected $frameworkPathName = 'framework';
  protected $frameworkUrl;

  protected $enterLocator = "//input[@type='submit' and @value='Вход']";
  protected $exitLocator = "//a[@href='/framework/framework/logout']";

  public static function get_slash()
  {
    return strtolower(substr(PHP_OS, 0, 3)) == 'win' ? '\\' : '/';
  }

  public function __construct()
  {
    if (!session_id()) {
      session_start();
    }

    $path = dirname(dirname(dirname(__FILE__)));
    $this->screenshotPath = $path.$this->get_slash().$this->screenshotsPathName;
    $this->screenshotUrl = $this->rootUrl.'/'.$this->screenshotsPathName;
    $this->frameworkUrl = $this->rootUrl.'/'.$this->frameworkPathName;

    parent::__construct();
  }

  protected function setUp()
  {
    $this->setBrowser('*googlechrome');
    $this->setBrowserUrl($this->frameworkUrl);
  }

  public function testTitle()
  {
    $this->open($this->frameworkUrl);
    $this->assertTitle('Home - Iris PHP Framework');
  }

  public function testLoginLogout()
  {
    $this->open($this->frameworkUrl.'/login');
    $this->assertTitle('Вход - Iris PHP Framework');

    // Correct Login and password
    $this->type('login', 'mnvx@yandex.ru');
    $this->type('password', '1');
    $this->click($this->enterLocator);
    $this->waitForPageToLoad(10000);
    $this->assertTrue($this->isElementPresent($this->exitLocator));      
    $this->assertTitle('My Profile - Iris PHP Framework');

    // Logged user try to open login page
    $this->open($this->frameworkUrl.'/login');
    $this->assertTrue($this->isElementPresent($this->exitLocator));      
    $this->assertTitle('Home - Iris PHP Framework');

    // Logout
    $this->click($this->exitLocator);
    $this->waitForPageToLoad(10000);
    $this->assertFalse($this->isElementPresent($this->exitLocator));      
    $this->assertTitle('Вход - Iris PHP Framework');

    // Incorrect Login and password
    $this->type('login', 'not_exist@yandex.ru');
    $this->type('password', 'not_exist');
    $this->click($this->enterLocator);
    $this->waitForPageToLoad(10000);
    $this->assertFalse($this->isElementPresent($this->exitLocator));      
    $this->assertTitle('Вход - Iris PHP Framework');
  }
}

?>