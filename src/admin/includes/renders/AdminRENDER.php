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
    }
}
?>
