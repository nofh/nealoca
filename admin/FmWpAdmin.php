<?php
class OrganismeAdmin 
{
	private $plugin_slug = PLUGIN_SLUG;
	protected static $instance = null;
	

	private function __construct()
	{
		// css et js
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// admin menu
        add_action( 'admin_menu', array( $this, 'register_menu_organisme_callback' ), 10, 0 );
	}

	public static function get_instance() 
	{
		if ( self::$instance == null ) 
		{
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function enqueue_admin_styles() 
	{
		wp_enqueue_style( 'css-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), VERSION );
	}

	public function enqueue_admin_scripts() 
	{
    }

    // creation menu
    public function register_menu_organisme_callback( )
    {
        //$adminRender = new AdminRENDER();

        add_menu_page( 'FmWp', 'FmWp', 'manage_options', 'fmwp_menu', null, EMP_IMG_ADMIN . 'icon_logo24.png');
    } 
}
?>
