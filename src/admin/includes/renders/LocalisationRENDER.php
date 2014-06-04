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
            add_meta_box( 'emplacement', 'Emplacement', array( $this, 'emplacement_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'acces', 'Acces', array( $this, 'acces_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'region', 'Région', array( $this, 'region_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'proche_de', 'Proche De', array( $this, 'proche_de_render' ), $post_type, 'advanced', 'default' );                
            add_meta_box( 'centres_interets', 'Centres Interets', array( $this, 'centres_interets_render' ), $post_type, 'advanced', 'default' );                
        }
    }

    public function emplacement_render( $post )
    {
        wp_nonce_field( 'emplacement_box', 'emplacement_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'emplacement', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 5 );
        wp_editor( $content, 'valeur_emplacement', $settings );
    }

    public function acces_render( $post )
    { 
        wp_nonce_field( 'acces_box', 'acces_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'acces', true );

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

    public function proche_de_render( $post )
    {
        wp_nonce_field( 'proche_de_box', 'proche_de_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'proche_de', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 5 );
        wp_editor( $content, 'valeur_proche_de', $settings );
    }

    public function centres_interets_render( $post )
    {
        wp_nonce_field( 'centres_interets_box', 'centres_interets_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'centres_interets', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 5 );
        wp_editor( $content, 'valeur_centres_interets', $settings );
    }

}
?>
