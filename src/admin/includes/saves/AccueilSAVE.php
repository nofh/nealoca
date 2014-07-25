<?php 
class AccueilSAVE extends CustomPostTypeSave
{
    public function __construct()
    {
        $config = array( );
        parent::__construct();
    }

    public function save_description_principale( $post_id )
    {
        $valeur_description_principale = sanitize_text_field( $_POST['valeur_description_principale'] );
    	update_post_meta( $post_id, PREFIX_META . 'description_principale', $valeur_description_principale );
    }

    public function save_description_secondaire( $post_id )
    {
        $valeur_description_secondaire = sanitize_text_field( $_POST['valeur_description_secondaire'] );

		// Update the meta field.
		update_post_meta( $post_id, PREFIX_META . 'description_secondaire', $valeur_description_secondaire );
    }

    public function save_description_tertiaire( $post_id )
    {
        $valeur_description_tertiaire = sanitize_text_field( $_POST['valeur_description_tertiaire'] );

		// Update the meta field.
		update_post_meta( $post_id, PREFIX_META . 'description_tertiaire', $valeur_description_tertiaire );
    }

    public function save_slogan( $post_id )
    {
        $valeur_slogan = sanitize_text_field( $_POST['valeur_slogan'] );

		// Update the meta field.
		update_post_meta( $post_id, PREFIX_META . 'slogan', $valeur_slogan );
    }

    public function save_slider( $post_id ) //REVOIR slider et gallerie -> ex appartement mais double?!
    {
       // recuprer le content 
        $gallerie_string = '';

        // recueprer les urls des images en attachement ( fct std de wp )
        $urls = array();
        $html = str_get_html( $_POST['valeur_slider_accueil'] );
        if( is_object( $html ) )
        {
            $liens = $html->find( 'a' );
            foreach( $liens as $element ) 
            {
                 $url =  str_replace( '\"', '', $element->href );
                 $urls[] = Utils::get_attachement_id_by_url( $url );

            }

            // preparation pour la save en db
            $urls_string = implode( ';', $urls );
        }

        // save en db 
        update_post_meta( $post_id, PREFIX_META . 'slider_accueil', $urls_string );
    }

    public function save_gallerie_accueil( $post_id )
    {
       // recuprer le content 
        $gallerie_string = '';

        // recueprer les urls des images en attachement ( fct std de wp )
        $urls = array();
        $html = str_get_html( $_POST['valeur_gallerie_accueil'] );
        if( is_object( $html ) )
        {
            $liens = $html->find( 'a' );
            foreach( $liens as $element ) 
            {
                 $url =  str_replace( '\"', '', $element->href );
                 $urls[] = Utils::get_attachement_id_by_url( $url );

            }

            // preparation pour la save en db
            $urls_string = implode( ';', $urls );
        }

        // save en db 
        update_post_meta( $post_id, PREFIX_META . 'gallerie_accueil', $urls_string );
    }

    public function save_disponibilite( $post_id )
    {
        $valeur_disponibilite = sanitize_text_field( $_POST['disponibilite'] );
		update_post_meta( $post_id, PREFIX_META . 'disponibilite', $valeur_disponibilite );
    }


}
?>
