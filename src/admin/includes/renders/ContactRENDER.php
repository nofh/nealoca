<?php 
class ContactRENDER
{
    private $post_type;


    public function __construct( $post_type ) 
    {
        $this->post_type = $post_type;

	add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( new ContactSAVE(), 'save' ) );
    }

    public function add_meta_box( $post_type ) 
    {
        $post_types = array( $this->post_type ); // limiter la creation des meta box au cpt organisme
        if ( in_array( $post_type, $post_types )) 
        {
            add_meta_box( 'description', 'Description', array( $this, 'description_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'infos_contact', 'Infos Contact', array( $this, 'infos_contact_render' ), $post_type, 'advanced', 'default' );                
           // add_meta_box( 'formulaire_contact', 'Formulaire Contact', array( $this, 'formulaire_contact_render' ), $post_type, 'advanced', 'default' );                
        }
    }

    public function description_render( $post )
    {
        wp_nonce_field( 'description_box', 'description_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'description', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 8 );
        wp_editor( $content, 'valeur_description', $settings );
    }

    public function infos_contact_render( $post )
    {
        wp_nonce_field( 'infos_contact_box', 'infos_contact_nonce' );
        
        $noms = array( 'heures', 'jours', 'nom_contact', 'tel_contact', 'email_contact' );
        foreach( $noms as $nom )
        {
            // tmp
            $tmp = get_post_meta( $post->ID, PREFIX_META . $nom, true );
            // final
            $content[$nom] = ( ! empty( $tmp ) ) ? $tmp : '';
        }

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
    }

 public function formulaire_contact_render( $post )
    {
        wp_nonce_field( 'formulaire_contact_box', 'formulaire_contact_nonce' );

        $destinataires_mail = get_post_meta( $post->ID, PREFIX_META . 'destinataires_mail', true );
        $destinataires = explode( ';', $destinataires_mail );

        $destinataire_un = ( array_key_exists( 0, $destinataires ) ) ? $destinataires[0] : ''; 
        $destinataire_deux = ( array_key_exists( 1, $destinataires ) ) ? $destinataires[1] : ''; 
        $destinataire_trois = ( array_key_exists( 2, $destinataires ) ) ? $destinataires[2] : ''; 

        echo "<table>";
        echo "<tr>";
        echo "<td><label for='destinataire_un'>" . __( 'Destinataire Principal', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='destinataire_un' name='destinataire_un' value='${destinataire_un}'></td>";
        //echo "<td><input type='text' id='destinataire_un' name='destinataire_un' value='${destinataire_un}'></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><label for='destinataire_deux'>" . __( 'Destinataire Secondaire', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='destinataire_deux' name='destinataire_deux' value='${destinataire_deux}'></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><label for='destinataire_trois'>" . __( 'Destinataire Tertiaire', TEXT_DOMAIN ) . "</label></td>";
        echo "<td><input type='text' id='destinataire_trois' name='destinataire_trois' value='${destinataire_trois}'></td>";
        echo "</tr>";
        echo "</table>";
    }
}
?>
