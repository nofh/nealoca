<?php
/**
 * Plugin Name.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Plugin_Name
 * @author  Your Name <email@example.com>
 */

class FmWpPublic
{
	private $plugin_slug = PLUGIN_SLUG;
	private static $instance = null;


    private function __construct() 
    {
		// css et js
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // shortcode
        $test_shc = new TestSHC();
	}
 
    public static function get_instance() 
	{

		// If the single instance hasn't been set, set it now.
		if ( self::$instance == null ) 
		{
			self::$instance = new self;
		}

		return self::$instance;
	}

	//
	public function get_plugin_slug() 
	{
		return $this->plugin_slug;
	}

	public function enqueue_styles() 
	{
		wp_enqueue_style( 'css-public-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), VERSION );
	}

	public function enqueue_scripts() 
	{
        // registre
		wp_enqueue_style( 'js-public-styles', plugins_url( 'assets/css/public.js', __FILE__ ), array(), VERSION );

        // localize
	}
}
