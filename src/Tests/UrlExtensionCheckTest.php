<?php

/**
 * @file
 * Contains \Drupal\action\Tests\BulkFormTest.
 */

namespace Drupal\fast404\Tests;

use Drupal\Core\Site\Settings;
use Drupal\simpletest\WebTestBase;

/**
 * Tests the functionality of fast 404.
 *
 * @group fast404
 */
class UrlExtensionCheckTest extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('fast404');

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests the Url not found markup.
   */
  public function testUrlCheck() {
    // Ensure path check isn't activated by default.
    $this->drupalGet('notdrupal');
    $this->assertResponse(404);
    $this->assertText('The requested page could not be found.');

    $settings = Settings::getAll();
    $settings['fast404_path_check'] = TRUE;
    $settings['fast404_html'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';
    new Settings($settings + Settings::getAll());

    $this->drupalGet('notdrupal');
    $this->assertResponse(404);
    $this->assertRaw('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "/notdrupal" was not found on this server.</p></body></html>');

  }
}
