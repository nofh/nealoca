<?php
class Posteur
{
    public function __construct()
    {
    }

    public function creer_post( $config )
    {

        $this->_creation_du_post( $config );
    }

    public function creer_page( $config )
    {
        $config['post_type'] = 'page';
        $this->_creation_du_post( $config );
    }

    public function creer_menu( $config )
    {
        $this->_creation_du_menu( $config );
    }

    // supprimer
    public function supprimer_post( $config )
    {
        $this->_suppression_du_post( $config );
    }

    public function supprimer_page( $config )
    {
        $this->_suppression_du_post( $config );
    }

    public function supprimer_menu( $config )
    {
        //add_filter( 'wp_nav_menu_args', array( $this, '_suppression_du_menu_callback' ) );
        $this->_suppression_du_menu( $config );
    }

    // --
    private function _creation_du_post( $config )
    {
        $post_a_creer = array(
            'post_content'   => $this->_get_post_option_config( $config, 'post_content' ),
            'post_title'     => $this->_get_post_option_config( $config, 'post_title' ),
            'post_status'    => $this->_get_post_option_config( $config, 'post_status' ),
            'post_type'      => $this->_get_post_option_config( $config, 'post_type' ),
            'post_author'    => $this->_get_post_option_config( $config, 'post_author' ),
            'page_template'  => $this->_get_post_option_config( $config, 'page_template' ),
        );

        $ok = wp_insert_post( $post_a_creer );

        return $ok;
    }

    private function _get_post_option_config( $config, $nom )
    {
        $ok = false;
        // verifier si existe
        if( array_key_exists( $nom, $config ) ) 
        {
            // TODO ajout de la conf du post 
            $tmp = $config[$nom];
            if( isset( $tmp ) && ! empty( $tmp ) )
            {
                $ok = $tmp;
            }
        }

        // valeur par defaut
        if( ! $ok )
        {
            switch( $nom )
            {
            case 'ID':
                $ok = ''; //FIXME particuleir !!!
                break;
            case 'post_content':
                $ok = "Silence Is Golden";
                break;
            case 'post_name':
                $ok = strtolower( $this->_get_post_option_config( $config, 'post_title' ) ); // == slug
                break;
            case 'post_title':
                $ok = 'Test-FmWp';
                break;
            case 'post_status':
                $ok = 'publish';
                break;
            case 'post_type':
                $ok = 'post';
                break;
            case 'post_author':
                $ok = 1; //FIXME ....pk 0??
                break;
            case 'ping_status':
                $ok = 'default_ping_status'; // defaut de wp
                break;
            case 'post_parent':
                $ok = 0; // default de wp
                break;
            case 'menu_order':
                $ok = 0; //default de wp
                break;
            case 'to_ping':
                $ok = ''; //default de wp
                break;
            case 'pinged':
                $ok = '';//default de wp
                break;
            case 'post_password':
                $ok = ''; //default de wp
                break;
            case 'post_excerpt':
                $ok = 'empty';
                break;
            case 'post_date':
                $ok = date("Y-m-d H:i:s");
                break;
            case 'post_date_gmt':
                $ok = date( "Y-m-d H:i:s" );
                break;
            case 'comment_status':
                $ok = 'closed';
                break;
            case 'post_category':
                $ok = ''; // default wp
                break;
            case 'tags_input':
                $ok = ''; // default wp
                break;
            case 'tax_input':
                $ok = ''; // default wp
                break;
            case 'page_template':
                $ok = '';//default wp
                break;
            }
        }


        return $ok;
    }

    private function _creation_du_menu( $config )
    {
        //http://wordpress.stackexchange.com/questions/44736/programmatically-add-a-navigation-menu-and-menu-items
        // Check if the menu exists
        $menu_exists = wp_get_nav_menu_object( $this->_get_menu_option_config( $config, 'menu_name' ) );

        // If it doesn't exist, let's create it.
        if( !$menu_exists )
        {
            // creer le menu
            $menu_id = wp_create_nav_menu( $this->_get_menu_option_config( $config, 'menu_name' ) );

            // ajout les pages au menu
            foreach( $this->_get_menu_option_config( $config, 'elements_menu') as $i => $nom )
            {
                $page_id = get_page_by_title( $nom );
                $page_permalink = get_permalink( $page_id );

                wp_update_nav_menu_item( $menu_id, 0, 
                    array(
                        'menu-item-title' =>  __( $nom, TEXT_DOMAIN ),
                        'menu-item-url' => $page_permalink, 
                        'menu-item-status' => 'publish'
                    ) );
            }
        }

        // active le menu ds le theme
        if( ! has_nav_menu( $this->_get_menu_option_config( $config, 'localisation_menu' ) ) )
        {
            $locations = get_theme_mod('nav_menu_locations');
            $locations[$this->_get_menu_option_config( $config, 'localisation_menu' )] = $menu_id;
            set_theme_mod( 'nav_menu_locations', $locations );
        }
    }

    private function _get_menu_option_config( $config, $nom )
    {
        $ok = false;
        // verifier si existe
        if( array_key_exists( $nom, $config ) ) 
        {
            // TODO ajout de la conf du post 
            $tmp = $config[$nom];
            if( isset( $tmp ) && ! empty( $tmp ) )
            {
                $ok = $tmp;
            }
        }

        // valeur par defaut
        if( ! $ok )
        {
            switch( $nom )
            {
            case 'menu_name':
                $ok = 'Menu-FmWp';
                break;
            case 'description_menu':
                $ok = "menu fmwp";
                break;
            case 'localisation_menu':
                $ok = '';
                break;
            case 'post_name':
                $ok = strtolower( $this->_get_post_option_config( $config, 'post_title' ) ); // == slug
                break;
            case 'post_title':
                $ok = 'Test-FmWp';
                break;
            case 'post_status':
                $ok = 'publish';
                break;
            }
        }


        return $ok;
    }

    // suppression
    private function _suppression_du_post( $config )
    {
        $post = get_page_by_title( $this->_get_menu_option_config( $config, 'post_title' ) );
        if( is_object( $post ) )
        {
            wp_delete_post( $post->ID );
        }
    }

    public function _suppression_du_menu( $config )
    {
        $db = Db::get_instance();
        $db->supprimer_menu( $this->_get_menu_option_config( $config, 'menu_name' ) );
        /*
        // TODO DELETE EN HARD 
        $locations = get_theme_mod('nav_menu_locations');
        $locations[$this->_get_menu_option_config( $config, 'localisation_menu' )] = '';
        set_theme_mod( 'nav_menu_locations', $locations );
         */
        //wp_delete_term( , 'category' ) 
    }
}
?>
