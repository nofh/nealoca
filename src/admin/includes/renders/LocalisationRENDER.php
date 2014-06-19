<?php 
class LocalisationRENDER
{
    private $post_type;


    public function __construct( $post_type ) 
    {
        $this->post_type = $post_type;

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( new LocalisationSAVE(), 'save' ) );
	}

    public function add_meta_box( $post_type ) 
    {
        $post_types = array( $this->post_type ); // limiter la creation des meta box au cpt organisme
        if ( in_array( $post_type, $post_types )) 
        {
            add_meta_box( 'acces', 'Acces', array( $this, 'acces_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'region', 'Région', array( $this, 'region_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'villages', 'Villages', array( $this, 'villages_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'centres_interets', 'Centres Interets', array( $this, 'centres_interets_render' ), $post_type, 'advanced', 'default' );                
        }
    }

    public function acces_render( $post )
    { 
        wp_nonce_field( 'acces_box', 'acces_nonce' );

        // content du texte accompagnateur
        $content = get_post_meta( $post->ID, PREFIX_META . 'acces', true );

        // content des noms
        $content_nom_arrivee = get_post_meta( $post->ID, PREFIX_META . 'nom_arrivee', true );
        $content_nom_depart_un = get_post_meta( $post->ID, PREFIX_META . 'nom_depart_un', true );
        $content_nom_depart_deux = get_post_meta( $post->ID, PREFIX_META . 'nom_depart_deux', true );
        $content_nom_depart_trois = get_post_meta( $post->ID, PREFIX_META . 'nom_depart_trois', true );
        $content_nom_depart_quatre = get_post_meta( $post->ID, PREFIX_META . 'nom_depart_quatre', true );

        // content des coordonees
        $content_coord_arrivee = get_post_meta( $post->ID, PREFIX_META . 'coord_arrivee', true );
        $content_coord_depart_un = get_post_meta( $post->ID, PREFIX_META . 'coord_depart_un', true );
        $content_coord_depart_deux = get_post_meta( $post->ID, PREFIX_META . 'coord_depart_deux', true );
        $content_coord_depart_trois = get_post_meta( $post->ID, PREFIX_META . 'coord_depart_trois', true );
        $content_coord_depart_quatre = get_post_meta( $post->ID, PREFIX_META . 'coord_depart_quatre', true );

        // adresse
        $content_adr_arrivee = get_post_meta( $post->ID, PREFIX_META . 'adr_arrivee', true );
        $content_adr_depart_un = get_post_meta( $post->ID, PREFIX_META . 'adr_depart_un', true );
        $content_adr_depart_deux = get_post_meta( $post->ID, PREFIX_META . 'adr_depart_deux', true );
        $content_adr_depart_trois = get_post_meta( $post->ID, PREFIX_META . 'adr_depart_trois', true );
        $content_adr_depart_quatre = get_post_meta( $post->ID, PREFIX_META . 'adr_depart_quatre', true );

        // 
        ?>
        <table>
        <tr>
          <th>Label</th>
          <th>Arrivée</th>
          <th>Départ Un</th> 
          <th>Départ Deux</th> 
          <th>Départ Trois</th> 
          <th>Départ Quatre</th> 
        </tr>
        <tr>
            <th>Nom</th>
            <td><input type="text" name="valeur_nom_arrivee" id="valeur_nom_arrivee" value="<?php echo $content_nom_arrivee?>"></td>
            <td><input type="text" name="valeur_nom_depart_un" id="valeur_nom_depart_un" value="<?php echo $content_nom_depart_un?>"></td>
            <td><input type="text" name="valeur_nom_depart_deux" id="valeur_nom_depart_deux" value="<?php echo $content_nom_depart_deux?>"></td>
            <td><input type="text" name="valeur_nom_depart_trois" id="valeur_nom_depart_trois" value="<?php echo $content_nom_depart_trois?>"></td>
            <td><input type="text" name="valeur_nom_depart_quatre" id="valeur_nom_depart_quatre" value="<?php echo $content_nom_depart_quatre?>"></td>
        </tr>
        <tr>
            <th>Coordonnees</th>
            <td><input type="text" name="valeur_coord_arrivee" id="valeur_coord_arrivee" value="<?php echo $content_coord_arrivee?>"></td>
            <td><input type="text" name="valeur_coord_depart_un" id="valeur_coord_depart_un" value="<?php echo $content_coord_depart_un?>"></td>
            <td><input type="text" name="valeur_coord_depart_deux" id="valeur_coord_depart_deux" value="<?php echo $content_coord_depart_deux?>"></td>
            <td><input type="text" name="valeur_coord_depart_trois" id="valeur_coord_depart_trois" value="<?php echo $content_coord_depart_trois?>"></td>
            <td><input type="text" name="valeur_coord_depart_quatre" id="valeur_coord_depart_quatre" value="<?php echo $content_coord_depart_quatre?>"></td>
        </tr>
        <tr>
            <th>Adresse</th>
            <td><input type="text" name="valeur_adr_arrivee" id="valeur_adr_arrivee" value="<?php echo $content_adr_arrivee?>"></td>
            <td><input type="text" name="valeur_adr_depart_un" id="valeur_adr_depart_un" value="<?php echo $content_adr_depart_un?>"></td>
            <td><input type="text" name="valeur_adr_depart_deux" id="valeur_adr_depart_deux" value="<?php echo $content_adr_depart_deux?>"></td>
            <td><input type="text" name="valeur_adr_depart_trois" id="valeur_adr_depart_trois" value="<?php echo $content_adr_depart_trois?>"></td>
            <td><input type="text" name="valeur_adr_depart_quatre" id="valeur_adr_depart_quatre" value="<?php echo $content_adr_depart_quatre?>"></td>
        </tr>

        </table>
        <?php

        // le ptit text accompagnateur
        $settings = array( 'media_buttons' => false, 'textarea_rows' => 5 );
        wp_editor( $content, 'valeur_acces', $settings );
    }
    
    public function region_render( $post )
    {
        wp_nonce_field( 'region_box', 'region_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'region', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 5 );
        wp_editor( $content, 'valeur_region', $settings );
    }

    public function villages_render( $post )
    {
        wp_nonce_field( 'villages_box', 'villages_nonce' );

        // recuper le nb de village
        $villages_nb = get_post_meta( $post->ID, PREFIX_META . 'villages_nb', true );
        echo "<ul id='liste_villages'>";
        for( $i = 0; $i < $villages_nb; $i++ )
        {
            $index_label = 'village_label_' . $i;
            $index_coord = 'village_coord_' . $i;

            $village_label = get_post_meta( $post->ID, PREFIX_META . $index_label, true );
            $village_coord = get_post_meta( $post->ID, PREFIX_META . $index_coord, true );

            // label
            echo "<li>";
            echo "<label for='${index_label}'>Label :</label>";
            echo "<input type='text' name='${index_label}' id='${index_label}' value='${village_label}'>";
 
            // coord
            echo "<label for='${index_coord}'>Coordonees :</label>";
            echo "<input type='text' name='${index_coord}' id='${index_coord}' value='${village_coord}'>";
        }
        echo "</ul>";

        // bouton et hidden
        echo "<!-- nb de villages -->";
        echo "<button type='button' id='ajouter_village'>Ajouter</button>";
        echo "<input type='hidden' name='villages_nb' id='villages_nb' value='${i}'>";

        // texte 
        $content = get_post_meta( $post->ID, PREFIX_META . 'texte_village', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 5 );
        wp_editor( $content, 'valeur_texte_village', $settings );
    }

    public function centres_interets_render( $post )
    {
        wp_nonce_field( 'centres_interets_box', 'centres_interets_nonce' );
        /*
            $content = get_post_meta( $post->ID, PREFIX_META . 'centres_interets', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 5 );
        wp_editor( $content, 'valeur_centres_interets', $settings );
         */

        // recuper le nb de ci
        $cis_nb = get_post_meta( $post->ID, PREFIX_META . 'cis_nb', true );
        echo "<ul id='liste_cis'>";
        for( $i = 0; $i < $cis_nb; $i++ )
        {
            $index_label = 'ci_label_' . $i;
            $index_coord = 'ci_coord_' . $i;
            $index_categorie = 'ci_categorie_' . $i;

            $ci_label = get_post_meta( $post->ID, PREFIX_META . $index_label, true );
            $ci_coord = get_post_meta( $post->ID, PREFIX_META . $index_coord, true );
            $ci_categorie = get_post_meta( $post->ID, PREFIX_META . $index_categorie, true );

            // label
            echo "<li>";
            echo "<label for='${index_label}'>Label :</label>";
            echo "<input type='text' name='${index_label}' id='${index_label}' value='${ci_label}'>";
 
            // coord
            echo "<label for='${index_coord}'>Coordonees :</label>";
            echo "<input type='text' name='${index_coord}' id='${index_coord}' value='${ci_coord}'>";

            // categorie
            echo "<select name='${index_categorie}'>";
            
            $selected = $this->selected_val( 'restaurant', $ci_categorie );
            echo "<option value='restaurant' $selected >Restaurant</option>";

            $selected = $this->selected_val( 'boulangerie', $ci_categorie );
            echo "<option value='boulangerie' $selected >Boulangerie</option>";
            
            echo "</select>";
            echo "</li>";
        }
        echo "</ul>";

        // bouton et hidden
        echo "<!-- nb de cis -->";
        echo "<button type='button' id='ajouter_ci'>Ajouter</button>";
        echo "<input type='hidden' name='cis_nb' id='cis_nb' value='${i}'>";

    }

    private function selected_val( $nom, $value )
    {
        $selected = '';

        if( $nom == $value )
        {
            $selected = "selected='selected'";
        }

        return $selected;
    }

}
?>
