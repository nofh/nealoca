<?php 
class AppartementSAVE extends CustomPostTypeSave
{
    public function __construct()
    {
        $config = array( );
    }

    public function save_description( $post_id )
    {
        $valeur_description = sanitize_text_field( $_POST['valeur_description'] );

		update_post_meta( $post_id, PREFIX_META . 'description', $valeur_description );
    }

    public function save_commodites( $post_id )
    {
        $valeur_commodites = sanitize_text_field( $_POST['valeur_commodites'] );

		update_post_meta( $post_id, PREFIX_META . 'commodites', $valeur_commodites );
    }

    public function save_photos( $post_id )
    {
        $valeur_photos = sanitize_text_field( $_POST['valeur_photos'] );

		update_post_meta( $post_id, PREFIX_META . 'photos', $valeur_photos );
    }
}
?>
