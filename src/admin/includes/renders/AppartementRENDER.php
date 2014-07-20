<?php 
class AppartementRENDER
{
    private $post_type;


    public function __construct( $post_type ) 
    {
        $this->post_type = $post_type;

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( new AppartementSAVE(), 'save' ) );
	}

    public function add_meta_box( $post_type ) 
    {
        $post_types = array( $this->post_type ); // limiter la creation des meta box au cpt organisme
        if ( in_array( $post_type, $post_types )) 
        {
            add_meta_box( 'description', 'Description', array( $this, 'description_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'commodites', 'Commodités', array( $this, 'commodites_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'gallerie', 'Gallerie', array( $this, 'gallerie_appartement_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'disponibilite', 'Disponibilite', array( $this, 'disponibilite_render' ), $post_type, 'advanced', 'default' );                
        }
    }

    public function description_render( $post )
    {
        wp_nonce_field( 'description_box', 'description_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'description', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 8 );
        wp_editor( $content, 'valeur_description', $settings );
    }

    public function commodites_render( $post )
    { 
        wp_nonce_field( 'commodites_box', 'commodites_nonce' );

        // recuper le nb de commodite
        $commodites_nb = get_post_meta( $post->ID, PREFIX_META . 'commodites_nb', true );
        echo "<ul id='liste_commodites'>";
        for( $i = 0; $i < $commodites_nb; $i++ )
        {
            $index_label = 'commodite_label_' . $i;
            $index_quantite = 'commodite_quantite_' . $i;

            $commodite_label = get_post_meta( $post->ID, PREFIX_META . $index_label, true );
            $commodite_quantite = get_post_meta( $post->ID, PREFIX_META . $index_quantite, true );

            // label
            echo "<li>";
            echo "<label for='${index_label}'>Label :</label>";
            echo "<input type='text' name='${index_label}' id='${index_label}' value='${commodite_label}'>";
 
            // quantite
            echo "<label for='${index_quantite}'>Quantités :</label>";
            echo "<input type='text' name='${index_quantite}' id='${index_quantite}' value='${commodite_quantite}'>";
        }
        echo "</ul>";

        // bouton et hidden
        echo "<!-- nb de commodites -->";
        echo "<button type='button' id='ajouter_commodite'>Ajouter</button>";
        echo "<input type='hidden' name='commodites_nb' id='commodites_nb' value='${i}'>";

    }
    
    public function gallerie_appartement_render( $post )
    {
        wp_nonce_field( 'gallerie_appartement', 'gallerie_appartement_nonce' );

        $gallerie_string = get_post_meta( $post->ID, PREFIX_META . 'gallerie_appartement', true );
        $gallerie = explode( ';', $gallerie_string );

        // recuperation des images 
        $content = "";
        foreach( $gallerie as $image_id ) 
        {
            $url = wp_get_attachment_url( $image_id ) ;
            $content .= "<a href='${url}'>" . wp_get_attachment_image( $image_id, 'medium' ) . "</a>"; 
        }

        $settings = array( 'media_buttons' => true, 'textarea_rows' => 12 );
        wp_editor( $content, 'valeur_gallerie_appartement', $settings );
    }

    public function disponibilite_render( $post )
    {
        wp_nonce_field( 'disponibilite', 'disponibilite_nonce' );


        $disponibilite = get_post_meta( $post->ID, PREFIX_META . 'disponibilite', true );
        $checked_oui = '';
        $checked_non = '';
        if( $disponibilite == 'oui' )
        {
            $checked_oui = "checked='checked'";
        }
        else
        {
            $checked_non = "checked='checked'";
        }

        echo "<input type='radio' name='disponibilite' value='oui' $checked_oui>Disponible"; 
        echo "<input type='radio' name='disponibilite' value='non' $checked_non>Non Disponible";
    }
}
?>
