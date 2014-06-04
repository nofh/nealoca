<?php 
class CustomPostTypeSave
{
    protected $config;

    
    public function __construct()
    {
    }

    public function get_arg_config( $nom )
    {

        switch( $nom )
        {
        case 'nom': 
            break;
        case 'slug':
            break;
        }
    }
    public function save( $post_id )
    {
        // recuperer les methodes save
        $methodes_save = get_class_methods( $this );

        foreach( $methodes_save as $methode_save ) // recuperer les methodes de l'object
        {
            if( substr_compare( $methode_save, 'save_', 0 ) > 1 ) // cherche les methodes save* save_texte_un -> nonce_texte_un
            {
                // recupere le nom ( fait le lien entre save et nonce save_nom nonce_nom
                $nom = substr( $methode_save, strlen( 'save_' ), strlen( $methode_save ) );
                $nom_nonce = $nom . '_nonce';

                // verifie 
                if( $this->verifier( $nom_nonce, $post_id ) )
                {
                    $this->$methode_save( $post_id );//appel une des methodes save*
                }
            }
        }
    }

    public function verifier( $nom_nonce, $post_id )
    {
        $ok = false;

        if( $this->verifier_nonce( $nom_nonce ) && $this->verifier_etat() && $this->verifier_droits( $post_id ) )
        {
           $ok = true; 
        }

        return $ok;
    }

    public function verifier_nonce( $nom_nonce )
    {
        $ok = false;
        if ( isset( $_POST[$nom_nonce] ) )
        {
            $ok = true;
        }
        //echo "<br> nonce ? $ok ";


        return $ok;
    }

    public function verifier_etat()
    {
        $ok = false;

        if( ! defined( 'DOING_AUTOSAVE' ) || ! DOING_AUTOSAVE ) 
        {
            $ok = true;
        }
        //echo "<br> etat? $ok ";


        return $ok;
    }

    public function verifier_droits( $post_id )
    {
        $ok = false;

        if ( 'page' == $_POST['post_type'] ) 
        {
			if ( current_user_can( 'edit_page', $post_id ) )
            {
                $ok = true;
            }
        } 
        else 
        {
			if ( current_user_can( 'edit_post', $post_id ) )
            {
                $ok = true;
            }
		}
        //echo "<br> droits? $ok ";


        return $ok;
    }
}
?>
