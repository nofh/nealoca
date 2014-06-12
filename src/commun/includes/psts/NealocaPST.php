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
        // choix du theme
        $this->activer_theme( 'NeaLoca' );

        // creation des pages et association avec un template
        $config_post_accueil = array( 'post_title' => 'Accueil', 'page_template' => 'accueil.php' );
        $this->creer_page( $config_post_accueil );

        $config_post_localisation = array( 'post_title' => 'Localisation', 'page_template' => 'localisation.php' );
        $this->creer_page( $config_post_localisation );

        $config_post_appartment = array( 'post_title' => 'Appartements', 'page_template' => 'appartements.php' );
        $this->creer_page( $config_post_appartment );

        $config_post_activite = array( 'post_title' => 'Activités', 'page_template' => 'activites.php' );
        $this->creer_page( $config_post_activite );

        $config_post_contact = array( 'post_title' => 'Contact', 'page_template' => 'contact.php' );
        $this->creer_page( $config_post_contact );

        $elements_menu = array( 'Localisation', 'Appartements', 'Activités', 'Contact'  );
        $config_menu = array( 'menu_name' => 'nealoca_menu', 'localisation_menu' => 'top-bar-r', 'elements_menu' => $elements_menu );
        $this->creer_menu( $config_menu );

        // choix d'un frong page
        $this->activer_front_page( 'Accueil' );
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
