<?php

/**
 * Plugin Name:       Modularity RSS reader
 * Plugin URI:        https://github.com/helsingborg-stad/modularity-rss
 * Description:       Get and display a combined feed from multiple rss sources
 * Version:           1.0.0
 * Author:            Sebastian Thulin
 * Author URI:        https://github.com/helsingborg-stad
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       modularity-rss
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('MODULARITYRSS_PATH', plugin_dir_path(__FILE__));
define('MODULARITYRSS_URL', plugins_url('', __FILE__));
define('MODULARITYRSS_TEMPLATE_PATH', MODULARITYRSS_PATH . 'templates/');
define('MODULARITYRSS_MODULE_PATH', MODULARITYRSS_PATH . 'source/php/');


load_plugin_textdomain('modularity-rss', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once MODULARITYRSS_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once MODULARITYRSS_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new MODULARITYRSS\Vendor\Psr4ClassLoader();
$loader->addPrefix('MODULARITYRSS', MODULARITYRSS_PATH);
$loader->addPrefix('MODULARITYRSS', MODULARITYRSS_PATH . 'source/php/');
$loader->register();

// Acf auto import and export
add_action('plugins_loaded', function () {
    $acfExportManager = new \AcfExportManager\AcfExportManager();
    $acfExportManager->setTextdomain('modularity-testimonials');
    $acfExportManager->setExportFolder(MODULARITYRSS_PATH . 'source/php/acf-fields/');
    $acfExportManager->autoExport(array(
        'mod-rss' => 'group_5a4e3c8ce5cf2',
    ));
    $acfExportManager->import();
});

/**
 * Registers the module
 */
add_action('plugins_loaded', function () {
    if (function_exists('modularity_register_module')) {
        modularity_register_module(
            MODULARITYRSS_MODULE_PATH,
            'App'
        );
    }
});
