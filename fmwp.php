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
 * Plugin Name:       NeaLoca 
 * Plugin URI:        @TODO
 * Description:       Location d'appartements
 * Version:           1.0.0
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
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/Db.php';
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/AjaxHandler.php';
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/Utils.php';
//cpts
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/cpts/ApiCustomPostType.php';
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/cpts/CustomPostType.php';
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/cpts/AccueilCPT.php'; 
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/cpts/LocalisationCPT.php'; 
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/cpts/AppartCPT.php'; 
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/cpts/PartenaireCPT.php'; 
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/cpts/ContactCPT.php'; 
//posteur
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/psts/Posteur.php'; 
require_once plugin_dir_path( __FILE__ ) . 'src/commun/includes/psts/NealocaPST.php'; 

//activation et desactivation
register_activation_hook( __FILE__, array( 'FmWpCommun', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'FmWpCommun', 'deactivate' ) );

// instancie le commun
add_action( 'plugins_loaded', array( 'FmWpCommun', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * FRONT
 *----------------------------------------------------------------------------*/
require_once plugin_dir_path( __FILE__ ) . 'src/public/FmWpPublic.php';
require_once plugin_dir_path( __FILE__ ) . 'src/public/includes/ShortCode.php';
require_once plugin_dir_path( __FILE__ ) . 'src/public/includes/TestSHC.php';
// vendor
//require_once plugin_dir_path( __FILE__ ) . 'src/public/assets/vendors/cool-php-captcha/captcha.php';


// instancie le front
add_action( 'plugins_loaded', array( 'FmWpPublic', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * ADMIN
 *----------------------------------------------------------------------------*/
if( is_admin() ) 
{
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/FmWpAdmin.php';
    // renders
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/renders/AdminRENDER.php';
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/renders/AccueilRENDER.php';
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/renders/LocalisationRENDER.php';
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/renders/AppartRENDER.php';
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/renders/PartenaireRENDER.php';
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/renders/ContactRENDER.php';
    // saves
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/saves/CustomPostTypeSave.php';
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/saves/AccueilSAVE.php';
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/saves/LocalisationSAVE.php';
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/saves/AppartSAVE.php';
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/saves/PartenaireSAVE.php';
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/includes/saves/ContactSAVE.php';
    // vendors
	include_once plugin_dir_path( __FILE__ ) . 'src/admin/assets/vendors/simple_html_dom.php';

	add_action( 'plugins_loaded', array( 'FmWpAdmin', 'get_instance' ) );
}
?>
