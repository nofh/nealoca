<?php
class TestCPT extends CustomPostType
{
    public function __construct( $nom)
    {
        $config = array();
        parent::__construct( $config );

        $this->setSupport( 'editor' );
       $this->ajouter_cpt_loop();
	  // $this->creer_render();
    }
}
?>
