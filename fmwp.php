<?php
/**
 * WordPress Micro FrameWork Plugin .
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @category Fmwp
 * @package  Organisme
 * @author   dendev <ddv@awt.be>
 *
 * @wordpress-plugin
 * Plugin Name:       FMWP
 * Plugin URI:        @TODO
 * Description:       Micro FrameWork
 * Version:           0.0.1
 * Author:            dendev
 * Text Domain:       fmwp_lang
 * Git Plugin URI:    https://github.com/nofh/fmwp.git
 * @license           GPL2 
 */

/*----------------------------------------------------------------------------*
 * COMMON 
 *----------------------------------------------------------------------------*/
require_once plugin_dir_path( __FILE__ ) . 'src/config.php';
require_once plugin_dir_path( __FILE__ ) . 'src/commun/FmWpCommun.php';
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/Utils.php';
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/cpts/CustomPostType.php';
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/cpts/TestCPT.php'; 
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/taxos/Taxonomie.php';
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/taxos/TestTAXO.php'; 

// activation et desactivation
register_activation_hook( __FILE__, array( 'FmWpPublic', 'activate' ) );
register_deactivation_hook (__FILE__, array( 'FmWpPublic', 'deactivate' ) );

// instancie le commun
add_action( 'plugins_loaded', array( 'FmWpCommun', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * FRONT
 *----------------------------------------------------------------------------*/
require_once plugin_dir_path( __FILE__ ) . 'src/public/FmWpPublic.php';
require_once plugin_dir_path( __FILE__ ) . 'src/public/includes/ShortCode.php';
require_once plugin_dir_path( __FILE__ ) . 'src/public/includes/TestSHC.php';

// instancie le front
add_action( 'plugins_loaded', array( 'FmWpPublic', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * ADMIN
 *----------------------------------------------------------------------------*/
if( is_admin() ) 
{
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/FmWpAdmin.php';
	add_action( 'plugins_loaded', array( 'FmWpAdmin', 'get_instance' ) );
}
