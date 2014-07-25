<?php 
class PartenaireSAVE extends CustomPostTypeSave
{
    public function __construct()
    {
        $config = array( );
    }

    public function save_description( $post_id )
    {
        $valeur_description = sanitize_text_field( $_POST['valeur_description'] );

		update_post_meta( $post_id, PREFIX_META . 'description', $valeur_description );
    }

    public function save_en_pratique( $post_id )
    {
        //print_r( $_POST );

        $noms = array( 'adresse_rue', 'adresse_numero', 'adresse_boite', 'adresse_ville', 'adresse_pays', 'heures', 'jours', 'nom_contact', 'tel_contact', 'email_contact', 'site_web_contact' );

        foreach( $noms as $nom )
        {
            if( array_key_exists( $nom, $_POST ) )
            {
                $valeur = sanitize_text_field( $_POST[$nom] );
                update_post_meta( $post_id, PREFIX_META . $nom, $valeur );
            }
        }
    }

    public function save_gallerie_activite( $post_id )
    {
        // recuprer le content 
        $gallerie_string = '';

        // recueprer les urls des images en attachement ( fct std de wp )
        $urls = array();
        $html = str_get_html( $_POST['valeur_gallerie_activite'] );
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
		update_post_meta( $post_id, PREFIX_META . 'gallerie_activite', $urls_string );
    }
}
?>
