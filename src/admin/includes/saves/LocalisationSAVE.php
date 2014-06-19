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

        // recup adresse
        $valeur_adr_arrivee = sanitize_text_field( $_POST['valeur_adr_arrivee'] );
        $valeur_adr_depart_un = sanitize_text_field( $_POST['valeur_adr_depart_un'] );
        $valeur_adr_depart_deux = sanitize_text_field( $_POST['valeur_adr_depart_deux'] );
        $valeur_adr_depart_trois = sanitize_text_field( $_POST['valeur_adr_depart_trois'] );
        $valeur_adr_depart_quatre = sanitize_text_field( $_POST['valeur_adr_depart_quatre'] );

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

        // save adresse
        update_post_meta( $post_id, PREFIX_META . 'adr_arrivee', $valeur_adr_arrivee );
        update_post_meta( $post_id, PREFIX_META . 'adr_depart_un', $valeur_adr_depart_un );
        update_post_meta( $post_id, PREFIX_META . 'adr_depart_deux', $valeur_adr_depart_deux );
        update_post_meta( $post_id, PREFIX_META . 'adr_depart_trois', $valeur_adr_depart_trois );
        update_post_meta( $post_id, PREFIX_META . 'adr_depart_quatre', $valeur_adr_depart_quatre );
    }

    public function save_region( $post_id )
    {
        $valeur_region = sanitize_text_field( $_POST['valeur_region'] );

        update_post_meta( $post_id, PREFIX_META . 'region', $valeur_region );
    }

    public function save_villages( $post_id )
    {
        $villages_nb = sanitize_text_field( $_POST['villages_nb'] );
        $villages_nb = intval( $villages_nb );

        // 
        for( $i =  0; $i < $villages_nb; $i++ )
        {
            $index_label_village = 'village_label_' . $i;
            $index_coord_village = 'village_coord_' . $i;
            $index_categorie_village = 'village_categorie_' . $i;

            // recuperation 
            $valeur_label_village = sanitize_text_field( $_POST[$index_label_village] );
            $valeur_coord_village = sanitize_text_field( $_POST[$index_coord_village] );

            // securiter
            if( ! empty( $valeur_label_village ) && ! empty( $valeur_coord_village ) )
            {
                // save
                update_post_meta( $post_id, PREFIX_META . $index_label_village, $valeur_label_village );
                update_post_meta( $post_id, PREFIX_META . $index_coord_village, $valeur_coord_village );
            }
            else
            {
                // supprimer 
                delete_post_meta( $post_id, PREFIX_META . $index_label_village, $valeur_label_village );
                delete_post_meta( $post_id, PREFIX_META . $index_coord_village, $valeur_coord_village );

                // mise a jour
                $villages_nb = ( $villages_nb <= 0 ) ? 0 : $villages_nb-1;
            }

            update_post_meta( $post_id, PREFIX_META . 'villages_nb', $villages_nb );
        }
        // texte
        $valeur_texte_village = sanitize_text_field( $_POST['valeur_texte_village'] );

        update_post_meta( $post_id, PREFIX_META . 'texte_village', $valeur_texte_village );

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
            $index_categorie_ci = 'ci_categorie_' . $i;

            // recuperation 
            $valeur_label_ci = sanitize_text_field( $_POST[$index_label_ci] );
            $valeur_coord_ci = sanitize_text_field( $_POST[$index_coord_ci] );
            $valeur_categorie_ci = sanitize_text_field( $_POST[$index_categorie_ci] );

            // securiter
            if( ! empty( $valeur_label_ci ) && ! empty( $valeur_coord_ci ) )
            {
                // save
                update_post_meta( $post_id, PREFIX_META . $index_label_ci, $valeur_label_ci );
                update_post_meta( $post_id, PREFIX_META . $index_coord_ci, $valeur_coord_ci );
                update_post_meta( $post_id, PREFIX_META . $index_categorie_ci, $valeur_categorie_ci );
            }
            else
            {
                // supprimer 
                delete_post_meta( $post_id, PREFIX_META . $index_label_ci, $valeur_label_ci );
                delete_post_meta( $post_id, PREFIX_META . $index_coord_ci, $valeur_coord_ci );
                delete_post_meta( $post_id, PREFIX_META . $index_categorie_ci, $valeur_categorie_ci );

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
