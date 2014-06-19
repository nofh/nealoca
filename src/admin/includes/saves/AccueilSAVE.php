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

    public function save_slider( $post_id )
    {
        // recuprer le content 
        $slider_string = '';

        // recueprer les urls des images en attachement ( fct std de wp )
        $hrefs = array();
        $html = str_get_html( $_POST['valeur_slider'] );
        if( is_object( $html ) )
        {
            $liens = $html->find( 'a' );
            foreach( $liens as $element ) 
            {
                 $hrefs[] =  str_replace( '\"', '', $element->href ) ;
            }
         //   print_r( $hrefs );

            // creation du slider 
            $slider = array();
            $images = get_children( "post_parent=$post_id&post_type=attachment&post_mime_type=image" );
            foreach( $images as $image )
            {
           //     echo $image->guid;
                // ds le slider on ne met que les attachement ds le post mais aussi ds la meta box slider 
                if( in_array( $image->guid, $hrefs ) )
                {
                    $slider[] = $image->ID;
                }
            }

            // preparation pour la save en db
            $slider_string = implode( ';', $slider );
           // print_r( $slider_string );
        }

        // save en db 
		update_post_meta( $post_id, PREFIX_META . 'slider', $slider_string );
    }

    public function save_gallerie_accueil( $post_id )
    {
        // recuprer le content 
        $gallerie_string = '';

        // recueprer les urls des images en attachement ( fct std de wp )
        $hrefs = array();
        $html = str_get_html( $_POST['valeur_gallerie_accueil'] );
        if( is_object( $html ) )
        {
            $liens = $html->find( 'a' );
            foreach( $liens as $element ) 
            {
                 $hrefs[] =  str_replace( '\"', '', $element->href ) ;
            }

            // creation du gallerie 
            $gallerie = array();
            $images = get_children( "post_parent=$post_id&post_type=attachment&post_mime_type=image" );
            foreach( $images as $image )
            {
                // ds le gallerie on ne met que les attachement ds le post mais aussi ds la meta box gallerie 
                if( in_array( $image->guid, $hrefs ) )
                {
                    $gallerie[] = $image->ID;
                }
            }

            // preparation pour la save en db
            $gallerie_string = implode( ';', $gallerie );
        }

        // save en db 
		update_post_meta( $post_id, PREFIX_META . 'gallerie_accueil', $gallerie_string );
    }

    public function save_disponibilite( $post_id )
    {
        $valeur_disponibilite = sanitize_text_field( $_POST['disponibilite'] );
		update_post_meta( $post_id, PREFIX_META . 'disponibilite', $valeur_disponibilite );
    }


}
?>
