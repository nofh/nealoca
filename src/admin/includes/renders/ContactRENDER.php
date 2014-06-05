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
            add_meta_box( 'photos', 'Photos', array( $this, 'photos_render' ), $post_type, 'advanced', 'default' );                
        }
    }

    public function description_render( $post )
    {
        wp_nonce_field( 'description_box', 'description_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'description', true );

        $settings = array( 'media_buttons' => false );
        wp_editor( $content, 'valeur_description', $settings );
    }

    public function photos_render( $post )
    {
        wp_nonce_field( 'photos_box', 'photos_nonce' );
        $content = get_post_meta( $post->ID, PREFIX_META . 'photos', true );

        $settings = array( 'media_buttons' => false, 'textarea_rows' => 8 );
        wp_editor( $content, 'valeur_photos', $settings );
    }
}
?>
