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
require_once plugin_dir_path( __FILE__ ) . 'config.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/CustomPostType.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/TestCPT.php'; 
require_once plugin_dir_path( __FILE__ ) . 'includes/Taxonomie.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/TestTAXO.php'; 

/*----------------------------------------------------------------------------*
 * FRONT
 *----------------------------------------------------------------------------*/
require_once plugin_dir_path( __FILE__ ) . 'public/FmWpFront.php';
require_once plugin_dir_path( __FILE__ ) . 'public/includes/ShortCode.php';
require_once plugin_dir_path( __FILE__ ) . 'public/includes/TestSHC.php';


// activation et desactivation
register_activation_hook( __FILE__, array( 'OrganismeFront', 'activate' ) );
register_deactivation_hook (__FILE__, array( 'OrganismeFront', 'deactivate' ) );

// instancie le front
add_action( 'plugins_loaded', array( 'OrganismeFront', 'get_instance' ) );
/*----------------------------------------------------------------------------*
 * ADMIN
 *----------------------------------------------------------------------------*/
if( is_admin() ) 
{
	include_once plugin_dir_path( __FILE__ ) . 'admin/FmWpAdmin.php';
	add_action( 'plugins_loaded', array( 'OrganismeAdmin', 'get_instance' ) );
}
