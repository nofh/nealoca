<?php 
class AccueilSAVE extends CustomPostTypeSave
{
    public function __construct()
    {
        $config = array( );
    }

    public function save_description_principale( $post_id )
    {
        //echo " TEXTE UN ";
       // print_r( $_POST );
        $valeur_description_principale = sanitize_text_field( $_POST['valeur_description_principale'] );

		// Update the meta field.
		update_post_meta( $post_id, PREFIX_META . 'description_principale', $valeur_description_principale );
    }

    public function save_description_secondaire( $post_id )
    {
        //echo " TEXTE UN ";
        // print_r( $_POST );
        $valeur_description_secondaire = sanitize_text_field( $_POST['valeur_description_secondaire'] );

		// Update the meta field.
		update_post_meta( $post_id, PREFIX_META . 'description_secondaire', $valeur_description_secondaire );
    }

    public function save_description_tertiaire( $post_id )
    {
        //echo " TEXTE UN ";
        // print_r( $_POST );
        $valeur_description_tertiaire = sanitize_text_field( $_POST['valeur_description_tertiaire'] );

		// Update the meta field.
		update_post_meta( $post_id, PREFIX_META . 'description_tertiaire', $valeur_description_tertiaire );
    }

    public function save_slogan( $post_id )
    {
        //echo " TEXTE UN ";
        // print_r( $_POST );
        $valeur_slogan = sanitize_text_field( $_POST['valeur_slogan'] );

		// Update the meta field.
		update_post_meta( $post_id, PREFIX_META . 'slogan', $valeur_slogan );
    }



}
?>
