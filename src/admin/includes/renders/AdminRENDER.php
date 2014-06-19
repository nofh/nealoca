<?php

class AdminRENDER
{

    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'creer_menu_admin_callback' ), 10, 0 );
    }

    public function creer_menu_admin_callback()
    {
        add_menu_page( 'NeaLoca', 'NeaLoca', 'manage_options', 'fmwp_menu', null, EMP_IMG_ADMIN . 'logo.png');
        add_submenu_page( 'fmwp_menu', 'Configuration', 'Configuration', 'manage_options', 'configuration_menu', array( $this, 'menu_admin_configuration_callback' ) );
    }

    public function menu_admin_configuration_callback()
    {
        include EMP_ADMIN_VIEWS . 'configuration.php' ;
    }
}
?>
