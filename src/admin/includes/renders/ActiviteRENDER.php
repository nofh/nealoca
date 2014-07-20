<?php 
class ActiviteRENDER
{
    private $post_type;


    public function __construct( $post_type ) 
    {
        $this->post_type = $post_type;

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( new ActiviteSAVE(), 'save' ) );
	}

    public function add_meta_box( $post_type ) 
    {
        $post_types = array( $this->post_type ); // limiter la creation des meta box au cpt organisme
        if ( in_array( $post_type, $post_types )) 
        {
            add_meta_box( 'description', 'Description', array( $this, 'description_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'en_pratique', 'En Pratique', array( $this, 'en_pratique_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'gallerie', 'Gallerie', array( $this, 'gallerie_activite_render' ), $post_type, 'advanced', 'default' );                
        }
    }

    public function description_render( $post )
    {
        wp_nonce_field( 'description_box', 'description_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'description', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 8 );
        wp_editor( $content, 'valeur_description', $settings );
    }

    public function en_pratique_render( $post )
    {
        wp_nonce_field( 'en_pratique_box', 'en_pratique_nonce' );

        $noms = array( 'adresse_rue', 'adresse_numero', 'adresse_boite', 'adresse_ville', 'adresse_pays', 'heures', 'jours', 'nom_contact', 'tel_contact', 'email_contact', 'site_web_contact' );
        foreach( $noms as $nom )
        {
            // tmp
            $tmp = get_post_meta( $post->ID, PREFIX_META . $nom, true );
            // final
            $content[$nom] = ( ! empty( $tmp ) ) ? $tmp : '';
        }

        // adresse
        echo "<h3>" . _e( 'Adresse', TEXT_DOMAIN ) . "</h3>";
        echo "<table>";
        echo "<tr>";
        echo "<td><label for='adresse_rue'>" . __( 'Rue', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='adresse_rue' name='adresse_rue' value='${content['adresse_rue']}'></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><label for='adresse_numero'>" . __( 'numero', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='adresse_numero' name='adresse_numero' value='${content['adresse_numero']}'></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><label for='adresse_boite'>" . __( 'boite', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='adresse_boite' name='adresse_boite' value='${content['adresse_boite']}'></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><label for='adresse_ville'>" . __( 'ville', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='adresse_ville' name='adresse_ville' value='${content['adresse_ville']}'></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><label for='adresse_pays'>" . __( 'pays', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='adresse_pays' name='adresse_pays' value='${content['adresse_pays']}'></td>";
        echo "</tr>";
        echo "</table>";

        // horaire
        echo "<h3>" . __( 'Horaires', TEXT_DOMAIN ) . "</h3>";
        echo "<table>";
        echo "<tr>";
        echo "<td><label for='adresse_heures'>" . __( 'heures', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='heures' name='heures' value='${content['heures']}'></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><label for='adresse_jours'>" . __( 'jours', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='jours' name='jours' value='${content['jours']}'></td>";
        echo "</tr>";
        echo "</table>";

        //  contact 
        echo "<h3>" . __( 'Contact', TEXT_DOMAIN ) . "</h3>";
        echo "<table>";
        echo "<tr>";
        echo "<td><label for='nom_contact'>" . __( 'Nom', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='nom_contact' name='nom_contact' value='${content['nom_contact']}'></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><label for='tel_contact'>" . __( 'Téléphone', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='tel_contact' name='tel_contact' value='${content['tel_contact']}'></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><label for='email_contact'>" . __( 'Email', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='email_contact' name='email_contact' value='${content['email_contact']}'></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><label for='site_web_contact'>" . __( 'Site Web', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='site_web_contact' name='site_web_contact' value='${content['site_web_contact']}'></td>";
        echo "</tr>";
        echo "</table>";

    }

    public function gallerie_activite_render( $post )
    {
        wp_nonce_field( 'gallerie_activite', 'gallerie_activite_nonce' );

        $gallerie_string = get_post_meta( $post->ID, PREFIX_META . 'gallerie_activite', true );
        $gallerie = explode( ';', $gallerie_string );

        // recuperation des images 
        $content = "";
        foreach( $gallerie as $image_id ) 
        {
            $url = wp_get_attachment_url( $image_id ) ;
            $content .= "<a href='${url}'>" . wp_get_attachment_image( $image_id, 'medium' ) . "</a>"; 
        }

        $settings = array( 'media_buttons' => true, 'textarea_rows' => 12 );
        wp_editor( $content, 'valeur_gallerie_activite', $settings );
    }

}
?>
