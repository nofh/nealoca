<?php
/**
 * Configuration du plugin
 *
 * @category Fmwp
 * @package  Organisme
 * @author   dendev <ddv@awt.be>
 */

// Version
define(VERSION, '1.0.0');
define(PLUGIN_SLUG, 'fmwp');

// prefix
define(PREFIX_PLUGIN, 'fw_'); // TODO readapation voir creation cpt pour le nom 
define(PREFIX_META, '_fw_'); // TODO passer de _ec vers og

// traduction
define(TEXT_DOMAIN, 'fmwp_langue');

// emplacement
define('EMP_PLUGIN', plugin_dir_path(__FILE__));
define('EMP_ADMIN_VIEWS', plugin_dir_path(__FILE__) . 'admin/views/');
define('EMP_PUBLIC_VIEWS', plugin_dir_path(__FILE__) . 'public/views/');

// Img  
define('URL_PLUGIN', plugins_url() . '/fmwp/');
define('EMP_IMG', URL_PLUGIN . 'assets/img/');
define('EMP_IMG_PUBLIC', URL_PLUGIN . 'public/assets/img/');
define('EMP_IMG_ADMIN', URL_PLUGIN . 'admin/assets/img/');

// vendors
define('EMP_VENDORS', URL_PLUGIN . 'assets/vendors/');

// global

// debug
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
error_reporting(E_ALL);
?>
