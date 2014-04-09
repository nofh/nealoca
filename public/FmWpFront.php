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

class OrganismeFront
{
	private $plugin_slug = PLUGIN_SLUG;
	private static $instance = null;


    private function __construct() 
    {

		// text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// css et js
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // custom post type 
        $test_cpt = new TestCPT( 'un' );
        $test_cpt = new TestCPT( 'deux' );

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

	public static function activate( $network_wide ) 
    {
        flush_rewrite_rules();
        
	}

	public static function deactivate( $network_wide ) 
	{
        flush_rewrite_rules();
	}

	//
	public function get_plugin_slug() 
	{
		return $this->plugin_slug;
	}

	//
	public function load_plugin_textdomain() 
	{
		$domain = TEXT_DOMAIN;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	public function enqueue_styles() 
	{
		wp_enqueue_style( 'css-public-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), VERSION );
	}

	public function enqueue_scripts() 
	{
        // registre

        // localize
	}
}
