<?php
class FmWpAdmin 
{
	private $plugin_slug = PLUGIN_SLUG;
	protected static $instance = null;
	

	private function __construct()
	{
		// css et js
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// admin menu
        new AdminRENDER();
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
		wp_enqueue_style( 'js-admin-styles', plugins_url( 'assets/css/admin.js', __FILE__ ), array(), VERSION );
    }

    // creation menu
    public function register_menu_organisme_callback( )
    {
        $slug_taxo = Utils::get_slug_taxo( TAXO_TEST );
        $slug_cpt = Utils::get_slug_cpt( 'un' );
        $url_taxo = admin_url( "edit-tags.php?taxonomy=$slug_taxo&post_type=$slug_cpt" );
        //$adminRender = new AdminRENDER();

        add_menu_page( 'FmWp', 'FmWp', 'manage_options', 'fmwp_menu', null, EMP_IMG_ADMIN . 'icon_logo24.png');
        add_submenu_page( 'fmwp_menu', 'Taxonomies des Cpts', "<a href='" . $url_taxo . "'>Taxo Cpts</a>", 'manage_options', 'taxo_cpts_menu', null ); 
        
    } 
}
?>
