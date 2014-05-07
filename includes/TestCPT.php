<?php
class TestCPT extends CustomPostType
{
    public function __construct( $nom)
    {
        $config = array( 'nom' => $nom, 'menu_position' => null, 'show_in_menu' => 'fmwp_menu' );
        parent::__construct( $config );

        $this->setSupport( 'editor' );
        $this->ajouter_cpt_loop();
        // $this->creer_render();
    }
}
?>
