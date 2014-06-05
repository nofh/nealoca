<?php 
class LocalisationSAVE extends CustomPostTypeSave
{
    public function __construct()
    {
        $config = array( );
    }

    public function save_emplacement( $post_id )
    {
        $valeur_emplacement = sanitize_text_field( $_POST['valeur_emplacement'] );

		update_post_meta( $post_id, PREFIX_META . 'emplacement', $valeur_emplacement );
    }

    public function save_acces( $post_id )
    {
        $valeur_acces = sanitize_text_field( $_POST['valeur_acces'] );

		update_post_meta( $post_id, PREFIX_META . 'acces', $valeur_acces );
    }

    public function save_region( $post_id )
    {
        $valeur_region = sanitize_text_field( $_POST['valeur_region'] );

		update_post_meta( $post_id, PREFIX_META . 'region', $valeur_region );
    }

    public function save_proche_de( $post_id )
    {
        $valeur_proche_de = sanitize_text_field( $_POST['valeur_proche_de'] );

		update_post_meta( $post_id, PREFIX_META . 'proche_de', $valeur_proche_de );
    }

    public function save_centres_interets( $post_id )
    {
        $valeur_centres_interets = sanitize_text_field( $_POST['valeur_centres_interets'] );

		update_post_meta( $post_id, PREFIX_META . 'centres_interets', $valeur_centres_interets );
    }
}
?>