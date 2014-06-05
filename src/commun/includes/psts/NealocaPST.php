<?php
class NealocaPST extends Posteur
{
    public function __construct( $mode )
    {
        if( $mode == 'creation' )
        {
            $this->_creation();
        }
        else if( $mode == 'suppression' )
        {
            $this->_suppression();
        }
    }

    public function _creation()
    {
        $config_post_localisation = array( 'post_title' => 'Localisation', 'page_template' => 'hero.php' );
        $this->creer_page( $config_post_localisation );

        $config_post_appartment = array( 'post_title' => 'Appartements', 'page_template' => 'hero.php' );
        $this->creer_page( $config_post_appartment );

        $config_post_activite = array( 'post_title' => 'Activités', 'page_template' => 'hero.php' );
        $this->creer_page( $config_post_activite );

        $config_post_contact = array( 'post_title' => 'Contact', 'page_template' => 'hero.php' );
        $this->creer_page( $config_post_contact );

        $elements_menu = array( 'Localisation', 'Appartements', 'Activités', 'Contact'  );
        $config_menu = array( 'menu_name' => 'nealoca_menu', 'localisation_menu' => 'top-bar-r', 'elements_menu' => $elements_menu );
        $this->creer_menu( $config_menu );

        $this->activer_theme( 'NeaLoca' );

    }

    public function _suppression()
    {
        $config_post_localisation = array( 'post_title' => 'Localisation' );
        $this->supprimer_page( $config_post_localisation );

        $config_post_appartment = array( 'post_title' => 'Appartements' );
        $this->supprimer_page( $config_post_appartment );

        $config_post_activite = array( 'post_title' => 'Activités' );
        $this->supprimer_page( $config_post_activite );

        $config_post_contact = array( 'post_title' => 'Contact' );
        $this->supprimer_page( $config_post_contact );

        $elements_menu = array( 'Localisation', 'Appartements', 'Activités', 'Contact' );
        $config_menu = array( 'menu_name' => 'nealoca_menu', 'elements_menu' => $elements_menu );
        $this->supprimer_menu( $config_menu );
    }
}
?>
