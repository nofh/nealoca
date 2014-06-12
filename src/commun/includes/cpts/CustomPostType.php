<?php
class CustomPostType
{
    private $_config;
    protected $post_type;
    protected $render;
 
 
    public function __construct( $config )
    {
        // args
        $this->setConfig( $config );
        $this->setPostType();
 
        // actions
        $callback = $this->getConfig( 'custom_callback' );  
        add_action( 'init', array( $this, $callback ) ); // creation du cpt via cpt_callback ou meth enfant donne en arg

        add_action( 'ajouter_support', array( $this, 'ajouter_support_callback' ), 10, 1 );

        if ( is_admin() ) 
        {
            add_action( 'load-post.php', array( $this, 'creer_render_callback' ) );
            add_action( 'load-post-new.php', array( $this, 'creer_render_callback' ) );
        }
    }

    /* getteurs et setteurs */
    /**
     * Set le tableau de configuration.
     * 
     * La configuration definit la personalisation du custom post type 
     *
     * @param mixed[] $config tableau associatif
     *
     * @return none
     */
    public function setConfig( $config )
    {
        $ok = false;
        if( is_array( $config ) )
        {
            $this->_config = $config;
            $ok = true;
        }

        return $ok;
    }

    public function setPostType()
    {
        $slug = $this->getConfig( 'slug' );
        if( $slug )
        {
            $this->post_type = PREFIX_PLUGIN . $slug . '_cpt';
        }
    }

    public function post_type_existe( $nom )
    {
        $post_types = get_post_types( '', 'names' ); 

        return in_array( PREFIX_PLUGIN . $nom . '_cpt', $post_types );
    }

    public function getConfig( $nom )
    {
        $ok = false;
        // verifier si existe
        if( array_key_exists( $nom, $this->_config ) ) 
        {
            $tmp = $this->_config[$nom];
            if( isset( $tmp ) && ! empty( $tmp ) )
            {
                $ok = $tmp;
            }
        }

        if( ! $ok )
        {
            switch( $nom )
            {
            case 'capability_type':
                $ok = 'post';
                break;
            case 'nom':
                $ok = 'test';
                // aucun cpt n'aurat le mm nom -> post_type
                if( $this->post_type_existe( $ok ) ) 
                {
                    $ok = str_shuffle( $ok ) . rand( 0, 100 );
                }
                break;
            case 'slug':
                $ok = strtolower( $this->getConfig( 'nom' ) );
                break;
            case 'show_in_menu':
                $ok = true;
                break;
            case 'show_in_nav_menus':
                $ok = false;
                break;
            case 'menu_position':
                $ok = ( $this->getConfig( 'show_in_menu' ) === true ) ? 100 : null;
                break;
            case 'custom_callback':
                $ok = 'cpt_callback';
                break;
            case 'class_render':
                $ok = ucfirst( $this->getConfig( 'nom' ) ) . 'RENDER';
                break;
            default:
                $ok = false;
            }
        }

        return $ok;
    }

    /* callback */
    /**
     * creation du cpt standard
     *
     *
     */
    public function cpt_callback()  
    {
        $nom  = $this->getConfig( 'nom' );
        $slug = $this->getConfig( 'slug' );
           
        $labels = array(
            'name'               => _x( ucfirst( $nom ), 'post type general name', TEXT_DOMAIN ),
            'singular_name'      => _x( ucfirst( $nom ), 'post type singular name', TEXT_DOMAIN ),
            'menu_name'          => _x( ucfirst( $nom ) . 's', 'admin menu', TEXT_DOMAIN ),
            'name_admin_bar'     => _x( ucfirst( $nom ), 'add new on admin bar', TEXT_DOMAIN ),
            'add_new'            => _x( 'Add New ' . ucfirst( $nom ), TEXT_DOMAIN ),
            'add_new_item'       => __( 'Add New ' . ucfirst( $nom ), TEXT_DOMAIN ),
            'new_item'           => __( 'New ' . ucfirst( $nom ), TEXT_DOMAIN ),
            'edit_item'          => __( 'Edit ' . ucfirst( $nom ), TEXT_DOMAIN ),
            'view_item'          => __( 'View ' . ucfirst( $nom ), TEXT_DOMAIN ),
            'all_items'          => __( 'All ' . ucfirst( $nom ) . 's', TEXT_DOMAIN ),
            'search_items'       => __( 'Search ' . ucfirst( $nom ) . 's', TEXT_DOMAIN ),
            'parent_item_colon'  => __( 'Parent ' . ucfirst( $nom ) . 's:', TEXT_DOMAIN ),
            'not_found'          => __( 'No ' . $nom . ' found.', TEXT_DOMAIN ),
            'not_found_in_trash' => __( 'No ' . $nom . 's found in Trash.', TEXT_DOMAIN ),
        );
 
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => $this->getConfig( 'show_in_menu' ),
            'show_in_nav_menus'  => $this->getConfig( 'show_in_nav_menus' ),           
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $slug . 's' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => $this->getConfig( 'menu_position' ),
            'supports'           => array( 'title' )
        );
 
        register_post_type( $this->post_type,  $args ); 
    }

    /**
     * Ajoute un rander au cpt
     */
    public function creer_render_callback()
    {
        $render = $this->getConfig( 'class_render' );

        if( $render )
        {
            $this->render = new $render( $this->post_type );
        }
    }

    /**
     * ajout les supports standard ds l'interface d'edition du cpt
     *
     */
    public function setSupport( $nom )
    {
        do_action( 'ajouter_support', $nom );
    }

    public function setSupports( $noms ) // inutile
    {
        do_action( 'ajouter_support', $noms  );
    }

    public function est_support_valide( $nom )
    {
        $ok = false;
        $supports_valide = array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats' );
        if( in_array( $nom , $supports_valide ) )
        {
            $ok = true;
        }

        return $ok;
    }

    public function ajouter_support_callback( $noms )
    {
        if( ! is_array( $noms ) )
        {
            $noms = array( $noms );
        }

        foreach( $noms as $nom )
        {
            if( $this->est_support_valide( $nom ) )
            {
                add_post_type_support( $this->post_type, $nom );
            }
        }
    }

    /**
     * Ajout le cpt ds la boucle standard de wp.
     *
     * Appel une callaback par defaut d'ajout ds la boucle.
     * ou une callaback personel donnees en argument.
     *
     * @params string $callback le fct/meth devant etre utiliser pour l'ajout ds la boucle
     *
     * @return none
     */
     public function ajouter_cpt_loop( $callback=null ) // permet d'etre redef par l'enfant
     {
         $callback = ( ! empty( $callback ) ) ? $callback : 'ajouter_cpt_loop_callback';
         add_filter( 'pre_get_posts', array( $this, $callback ) );
     }

    /**
     * Callback par defaut d'ajout ds la loop de wp.
     *
     * Modifie la query wordpresss pour integrer le cpt.
     *
     * @params mixed[] $query query fournit pas wp lors de l'appel de la callback
     *
     * @return mixed[] la query modifier 
     */
    public function ajouter_cpt_loop_callback( $query )// ajout le cpt ds la loop des single.php // TODO trop specifique
    {
        if ( ( is_single( )  || is_page() || is_home() ) && $query->is_main_query()  ) // TODO aps tres subtil, inlut cpt partout 
        {
            // recup l'existant
            $types_ds_loop = $query->get( 'post_type' );

            // tout en array
            if( is_string( $types_ds_loop ) )
            {
                $types_ds_loop = array( $types_ds_loop );
            } 

            // ajout des post et page
            if( ! in_array( 'post', $types_ds_loop ) )
            {
                $types_ds_loop[] = 'post';
            }
            if( ! in_array( 'page', $types_ds_loop ) )
            {
                $types_ds_loop[] = 'page';
            }

            // ajout du cpt 
            $types_ds_loop[] = $this->post_type;

            // ajout effectif
            $query->set( 'post_type', $types_ds_loop );
        }

        return $query;
    }
    /**
     * Callaback d'interdiction de creation d'un nouveau cpt
     *
     * Est appeler par une autres methodes elle mm appeler par un enfant.
     *
     * @return none
     */
    public function disable_new_post_callback()
    {
        if( get_current_screen()->post_type == $this->post_type )
            wp_die( "You ain't allowed to do that!" );
    }

    /**
     * Empeche la création de cpt organisme.
     *
     * Les customes post type organisme ne sont pas creer ds wp.
     * wp se charge de les afficher mais ne creer pas, il utilise les infos fournit pas le webservice 
     *
     * @see Import
     *
     * @return none
     */
    public function desactiver_nouveau_cpt() // FIXME fwmp: callback? -> non callback
    {
        add_action( 'load-post-new.php', array( $this, 'disable_new_post_callback' ) );
    }

        /**
     * Modifie l'affichage admin de la liste des cpts organisme.
     *
     * Ajout des colonnes a la liste d'affichage.
     *
     * @return none 
     */
    public function modifier_affichage_liste_admin()
    {
        add_filter( 'manage_' . $this->post_type . '_posts_columns', array( $this, 'modifier_affichage_liste_admin_callback' ) );

        add_action( 'manage_posts_custom_column', array( $this, 'remplire_colonne_liste_admin_callback' ), 10, 2 );
    }

    /**
     * Callback pour l'ajout de colonnes.
     *
     * Détermine le nom et le slug des colonnes a ajouter
     *
     * @param mixed[] $colonne array des colonnes existantes 
     *
     * @return $colonnes tableau des colonnes existantes plus celles ajouter
     */
    public function modifier_affichage_liste_admin_callback( $colonne, $ajouts ) 
    {
        return array_merge( $colonne, array( 'localite' => 'Localité', 'nomcontact' => 'Nom Contact', 'prenomcontact' => 'Prenom Contact' ) );
    }

    /**
     * Ajoute les valeurs a la colonne.
     *
     * les args sont remplit par wp lors de l'appel de la callback
     *
     * @param mixed[]    $colonne array des clonnes existantes
     * @param string|int $post_id id du post dont on veut utiliser une des valeurs pour l'afficher ds la colonne 
     *
     * @return none
     */
    public function remplire_colonne_liste_admin_callback( $colonne, $post_id )
    {
        if( $colonne == 'localite' )
        {
            $valeur = get_post_meta( $post_id, PREFIX_META . 'adresse_organisme', true );
            $elements = explode( ';', $valeur );
            $valeur = ( ! empty( $elements[4] ) ) ? $elements[4] : ' - '; 
        }
        else if( $colonne == 'nomcontact' )
        {
            $valeur = get_post_meta( $post_id, PREFIX_META . 'nom_contact', true );
        }
        else if( $colonne == 'prenomcontact' )
        {
            $valeur = get_post_meta( $post_id, PREFIX_META . 'prenom_contact', true );
        }
    }

    public function rediriger_template_cpt()
    {
        add_filter( 'single_template', array( $this, 'rediriger_template_cpt_callback' ) );
    }

    public function rediriger_template_cpt_callback( $template_a_utiliser ) 
    {
        global $post;

        if( $post->post_type == $this->post_type ) // eviter la redirection des pages ou post
        {
            $nom_template = 'single-' . $this->getConfig( 'slug' ) . '.php';
            $template_theme = locate_template( array( $nom_template ) );
            if( $template_theme ) 
            {
                $template_a_utiliser = $template_theme; 
            }
            else 
            {
                $template_a_utiliser = EMP_PUBLIC_VIEWS . $nom_template; //template plugin
            }
        }

        return $template_a_utiliser;
    }
}
?>
