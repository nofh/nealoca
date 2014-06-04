<?php 
class AccueilRENDER
{
    private $post_type;


    public function __construct( $post_type ) 
    {
        $this->post_type = $post_type;

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( new AccueilSAVE(), 'save' ) );
	}

    public function add_meta_box( $post_type ) 
    {
        $post_types = array( $this->post_type ); // limiter la creation des meta box au cpt organisme
        if ( in_array( $post_type, $post_types )) 
        {
            add_meta_box( 'description_principale', 'Description Principale', array( $this, 'description_principale_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'description_secondaire', 'Description Secondaire', array( $this, 'description_secondaire_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'description_tertiaire', 'Description Tertiaire', array( $this, 'description_tertiaire_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'slogan', 'Slogan', array( $this, 'slogan_render' ), $post_type, 'advanced', 'default' );                
        }
    }

    public function description_principale_render( $post )
    {
        wp_nonce_field( 'description_principale_box', 'description_principale_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'description_principale', true );

        $settings = array( 'media_buttons' => false );
        wp_editor( $content, 'valeur_description_principale', $settings );
    }

    public function description_secondaire_render( $post )
    { 
        wp_nonce_field( 'description_secondaire_box', 'description_secondaire_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'description_secondaire', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 12 );
        wp_editor( $content, 'valeur_description_secondaire', $settings );
    }
    
    public function description_tertiaire_render( $post )
    {
        wp_nonce_field( 'description_tertiaire_box', 'description_tertiaire_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'description_tertiaire', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 8 );
        wp_editor( $content, 'valeur_description_tertiaire', $settings );
    }

    public function slogan_render( $post )
    {
        wp_nonce_field( 'slogan_box', 'slogan_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'slogan', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 2 );
        wp_editor( $content, 'valeur_slogan', $settings );

    }

}
?>
