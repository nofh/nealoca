<?php
class PartenaireCPT extends CustomPostType
{
    public function __construct()
    {
        $config = array( 'nom' => 'Partenaire', 'menu_position' => null, 'show_in_menu' => 'fmwp_menu', 'show_in_nav_menus' => true );
        parent::__construct( $config );

        $this->ajouter_cpt_loop();
        $this->rediriger_template_cpt();
    }
}
?>
