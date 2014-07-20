<?php 
class AppartementSAVE extends CustomPostTypeSave
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

    public function save_commodites( $post_id )
    {
        //print_r( $_POST );

        $commodites_nb = sanitize_text_field( $_POST['commodites_nb'] );
        $commodites_nb = intval( $commodites_nb );

        // 
        for( $i =  0; $i < $commodites_nb; $i++ )
        {
            $index_label_commodite = 'commodite_label_' . $i;
            $index_quantite_commodite = 'commodite_quantite_' . $i;

            // recuperation 
            if( array_key_exists( $index_label_commodite, $_POST ) && array_key_exists( $index_quantite_commodite, $_POST ) )
            {
                $valeur_label_commodite = sanitize_text_field( $_POST[$index_label_commodite] );
                $valeur_quantite_commodite = sanitize_text_field( $_POST[$index_quantite_commodite] );

                // securiter
                if( ! empty( $valeur_label_commodite ) && ! empty( $valeur_quantite_commodite ) )
                {
                    // save
                    update_post_meta( $post_id, PREFIX_META . $index_label_commodite, $valeur_label_commodite );
                    update_post_meta( $post_id, PREFIX_META . $index_quantite_commodite, $valeur_quantite_commodite );
                }
                else
                {
                    // supprimer 
                    delete_post_meta( $post_id, PREFIX_META . $index_label_commodite, $valeur_label_commodite );
                    delete_post_meta( $post_id, PREFIX_META . $index_quantite_commodite, $valeur_quantite_commodite );

                    // mise a jour
                    $commodites_nb = ( $commodites_nb <= 0 ) ? 0 : $commodites_nb-1;
                }

                update_post_meta( $post_id, PREFIX_META . 'commodites_nb', $commodites_nb );
            }
        }
    }

    public function save_gallerie_appartement( $post_id )
    {
        // recuprer le content 
        $gallerie_string = '';

        // recueprer les urls des images en attachement ( fct std de wp )
        $urls = array();
        $html = str_get_html( $_POST['valeur_gallerie_appartement'] );
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

            // save en db 
            update_post_meta( $post_id, PREFIX_META . 'gallerie_appartement', $urls_string );
        }

    }

}
?>
