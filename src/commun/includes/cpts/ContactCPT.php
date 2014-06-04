<?php
class ContactCPT extends CustomPostType
{
    public function __construct()
    {
        $config = array( 'nom' => 'Contact', 'menu_position' => null, 'show_in_menu' => 'fmwp_menu' );
        parent::__construct( $config );

        $this->setSupport( 'editor' );
        $this->ajouter_cpt_loop();
        $this->modifier_affichage_liste_admin();
        // $this->creer_render();
    }
}
?>
