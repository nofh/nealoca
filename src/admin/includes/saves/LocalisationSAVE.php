<?php 
class LocalisationSAVE extends CustomPostTypeSave
{
    public function __construct()
    {
        $config = array( );
    }

    public function save_acces( $post_id )
    {
        // recup texte
        $valeur_acces = sanitize_text_field( $_POST['valeur_acces'] );

        // recup noms
        $valeur_nom_arrivee = sanitize_text_field( $_POST['valeur_nom_arrivee'] );
        $valeur_nom_depart_un = sanitize_text_field( $_POST['valeur_nom_depart_un'] );
        $valeur_nom_depart_deux = sanitize_text_field( $_POST['valeur_nom_depart_deux'] );
        $valeur_nom_depart_trois = sanitize_text_field( $_POST['valeur_nom_depart_trois'] );
        $valeur_nom_depart_quatre = sanitize_text_field( $_POST['valeur_nom_depart_quatre'] );

        // recup coordonees
        $valeur_coord_arrivee = sanitize_text_field( $_POST['valeur_coord_arrivee'] );
        $valeur_coord_depart_un = sanitize_text_field( $_POST['valeur_coord_depart_un'] );
        $valeur_coord_depart_deux = sanitize_text_field( $_POST['valeur_coord_depart_deux'] );
        $valeur_coord_depart_trois = sanitize_text_field( $_POST['valeur_coord_depart_trois'] );
        $valeur_coord_depart_quatre = sanitize_text_field( $_POST['valeur_coord_depart_quatre'] );


        // save texte
		update_post_meta( $post_id, PREFIX_META . 'acces', $valeur_acces );

        // save noms
		update_post_meta( $post_id, PREFIX_META . 'nom_arrivee', $valeur_nom_arrivee);
		update_post_meta( $post_id, PREFIX_META . 'nom_depart_un', $valeur_nom_depart_un );
		update_post_meta( $post_id, PREFIX_META . 'nom_depart_deux', $valeur_nom_depart_deux );
		update_post_meta( $post_id, PREFIX_META . 'nom_depart_trois', $valeur_nom_depart_trois );
		update_post_meta( $post_id, PREFIX_META . 'nom_depart_quatre', $valeur_nom_depart_quatre );

        // save coordonnes
		update_post_meta( $post_id, PREFIX_META . 'coord_arrivee', $valeur_coord_arrivee );
		update_post_meta( $post_id, PREFIX_META . 'coord_depart_un', $valeur_coord_depart_un );
		update_post_meta( $post_id, PREFIX_META . 'coord_depart_deux', $valeur_coord_depart_deux );
		update_post_meta( $post_id, PREFIX_META . 'coord_depart_trois', $valeur_coord_depart_trois );
		update_post_meta( $post_id, PREFIX_META . 'coord_depart_quatre', $valeur_coord_depart_quatre );
    }

    public function save_region( $post_id )
    {
        $valeur_region = sanitize_text_field( $_POST['valeur_region'] );

		update_post_meta( $post_id, PREFIX_META . 'region', $valeur_region );
    }

    public function save_villages( $post_id )
    {
        $valeur_villages = sanitize_text_field( $_POST['valeur_villages'] );

		update_post_meta( $post_id, PREFIX_META . 'villages', $valeur_villages );
    }

    public function save_centres_interets( $post_id )
    {
       // print_r( $_POST );
        //recuperer le nombre de ci
        $cis_nb = sanitize_text_field( $_POST['cis_nb'] );
        $cis_nb = intval( $cis_nb );

        // 
        for( $i =  0; $i < $cis_nb; $i++ )
        {
            $index_label_ci = 'ci_label_' . $i;
            $index_coord_ci = 'ci_coord_' . $i;

            // recuperation 
            $valeur_label_ci = sanitize_text_field( $_POST[$index_label_ci] );
            $valeur_coord_ci = sanitize_text_field( $_POST[$index_coord_ci] );

            // securiter
            if( ! empty( $valeur_label_ci ) && ! empty( $valeur_coord_ci ) )
            {
                // save
                update_post_meta( $post_id, PREFIX_META . $index_label_ci, $valeur_label_ci );
                update_post_meta( $post_id, PREFIX_META . $index_coord_ci, $valeur_coord_ci );
            }
            else
            {
                // supprimer 
                delete_post_meta( $post_id, PREFIX_META . $index_label_ci, $valeur_label_ci );
                delete_post_meta( $post_id, PREFIX_META . $index_coord_ci, $valeur_coord_ci );

                // mise a jour
                $cis_nb = ( $cis_nb <= 0 ) ? 0 : $cis_nb-1;
            }

            update_post_meta( $post_id, PREFIX_META . 'cis_nb', $cis_nb );

        }

    
        /*
        $valeur_centres_interets = sanitize_text_field( $_POST['valeur_centres_interets'] );

		update_post_meta( $post_id, PREFIX_META . 'centres_interets', $valeur_centres_interets );
         */
    }
}
?>
