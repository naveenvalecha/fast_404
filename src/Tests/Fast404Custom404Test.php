<?php

namespace Drupal\fast404\Tests;

use Drupal\Core\Site\Settings;
use Drupal\simpletest\WebTestBase;

/**
 * Tests the path checking functionality.
 *
 * @group fast404
 */
class Fast404Custom404Test extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['system_test', 'fast404'];

  protected $adminUser;

  protected function setUp() {
    parent::setUp();
    // Create an administrative user.
    $this->adminUser = $this->drupalCreateUser(['administer site configuration']);
    $this->adminUser->roles[] = 'administrator';
    $this->adminUser->save();
  }

  /**
   * Tests the Url not found markup.
   */
  public function testCustom404Check() {

    $this->drupalLogin($this->adminUser);
    $settings = Settings::getAll();
    $settings['fast404_return_gone'] = TRUE;
    $settings['fast404_HTML_error_page'] = './custom-404.html';
    new Settings($settings + Settings::getAll());
    $this->drupalLogout();
    $this->drupalGet('unfound.flv');
    $this->assertResponse(404);
    $this->assertText('The requested URL "/unfound.flv" was not found on this server (Fast 404).');
//    $this->assertRaw('<html>
//    <head>
//      <title>404 Not Found</title>
//    </head>
//    <body>
//      <h1>Custom 404!</h1>
//    </body>
//   </html>');
    $this->assertRaw('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "/unfound.flv" was not found on this server (Fast 404).</p></body></html>');
  }
}
