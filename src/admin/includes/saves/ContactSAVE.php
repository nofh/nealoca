<?php 
class ContactSAVE extends CustomPostTypeSave
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

   
    public function save_infos_contact( $post_id )
    {
     //  print_r( $_POST );

        $noms = array( 'heures', 'jours', 'nom_contact', 'tel_contact', 'email_contact' );

        foreach( $noms as $nom )
        {
            if( array_key_exists( $nom, $_POST ) )
            {
                $valeur = sanitize_text_field( $_POST[$nom] );
                update_post_meta( $post_id, PREFIX_META . $nom, $valeur );

                if( $nom == 'nom_contact' || $nom == 'email_contact' || $nom == 'tel_contact' )
                {
                    update_option( PREFIX_META . $nom, $valeur );
                }
            }
        }
    }

    public function save_formulaire_contact( $post_id )
    {
        //print_r( $_POST );

        $tmps = array();
        $tmps[] = ( !empty( $_POST['destinataire_un'] ) ) ? $_POST['destinataire_un'] : false;
        $tmps[] = ( !empty( $_POST['destinataire_deux'] ) ) ? $_POST['destinataire_deux'] : false;
        $tmps[] = ( !empty( $_POST['destinataire_trois'] ) ) ? $_POST['destinataire_trois'] : false;

        foreach( $tmps as $cle => $tmp )
        {
            if( ! $tmp )
            {
                unset( $tmps[$cle] );
            } 
        }

        $valeur_destinataires = implode( ';', $tmps );
        
		update_post_meta( $post_id, PREFIX_META . 'destinataires_mail', $valeur_destinataires );
    }
}
?>
