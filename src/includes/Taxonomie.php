<?php
/**
 * Gere la création de taxonomie.
 *
 * Ne doit pas etre instancier. Son utilisation ce fait via un enfant.
 * Cree une taxonomie d'apres la configuration donnée en argument.
 * 
 * @param mixed[] $_config tableau associatif ayant pour les clees les options wp a customiser avec les valeurs.
 *
 * @category Organisme
 * @package  Structure
 * @author   dendev <ddv@awt.be>
 */

/**
 * Gere la création de taxonomie.
 *
 * Ne doit pas etre instancier. Son utilisation ce fait via un enfant.
 * Cree une taxonomie d'apres la configuration donnée en argument.
 * 
 * @param mixed[] $_config tableau associatif ayant pour les clees les options wp a customiser avec les valeurs.
 *
 * @category Organisme
 * @package  Structure
 * @author   dendev <ddv@awt.be>
 */
class Taxonomie
{
    private $_config;

    /**
     * Constructeur.
     *
     * Réalise l'init wp pour la création de la taxonomie.
     * La création de la taxonomie se fait ds la methode  creer_taxo_callback
     *
     * @param mixed[] $_config tableau associatif ayant pour les clees les options wp a customiser avec les valeurs.
     *
     * @return none
     */
    public function __construct( $config )
    {
        $this->_config = $config;
        
        add_action( 'init', array( $this, 'creer_taxo_callback' ), 0 );
    }

    /**
     * Récupere la valeur de configuration.
     *
     * Permet de recuperer la valeur de configuraton demander via l'argument.
     * Si la valeur de configuration n'existe pas alors retourne une valeur par defaut.
     * Si l'option de configuration demander n'existe pas alors renvoi false
     * Certaine options peuvent renvoyer false, ce n'est pas pertinent de dire que si return == false alors l'option n'existe pas!
     * 
     * @param string $nom le nom de l'option de configuration dont on souhaite la valeur ( le nom equivaut avec le nom de l'option taxo de wp ).
     * 
     * @return mixed false si n'existe pas ou la valeur de l'option.
     */
    public function get_arg_config( $nom )
    {
          // verifier si existe
        if (array_key_exists( $nom, $this->_config ) ) 
        {
            $valeur = $this->_config[$nom];
        }
        else // renvoi une valeur par defaut adapter
        {
            switch( $nom )
            {
            case 'post_types':
                $valeur = array( 'post' );
                break;
            case 'hierarchical':
                $valeur = false;
                break;
            case 'nom':
                $valeur = 'test';
                break;
            case 'slug':
                $valeur = strtolower( $this->get_arg_config( 'nom' ) );
                break;
            default:
                $valeur = false;
            }
        }

        return $valeur;
    }

    /**
     * Cree une taxonomie.
     *
     * Cree la taxonomie d'apres les informations de configuration.
     * La methode ne doit pas etre appeler directement.
     * Son utilisation se fait via l'action wp declencher ds le constructeur.
     * L'enfant heritant de Taxonomie est celui qui va appeler ( indirectement ) cette methode.
     *
     */
    public function creer_taxo_callback()
    {
        $nom  = $this->get_arg_config( 'nom' );
        $slug = $this->get_arg_config( 'slug');

        $labels = array(
            'name'              => _x( ucfirst( $nom ) . 's', 'taxonomy general name', TEXT_DOMAIN ),
            'singular_name'     => _x( ucfirst( $nom ), 'taxonomy singular name', TEXT_DOMAIN ),
            'search_items'      => __( 'Search ' . ucfirst( $nom ) . 's', TEXT_DOMAIN ),
            'all_items'         => __( 'All ' .  ucfirst( $nom ), TEXT_DOMAIN ),
            'parent_item'       => __( 'Parent ' . ucfirst( $nom ), TEXT_DOMAIN ),
            'parent_item_colon' => __( 'Parent ' . ucfirst( $nom ) . ':', TEXT_DOMAIN ),
            'edit_item'         => __( 'Edit ' .  ucfirst( $nom ), TEXT_DOMAIN ),
            'update_item'       => __( 'Update ' . ucfirst( $nom ), TEXT_DOMAIN ),
            'add_new_item'      => __( 'Add New ' . ucfirst( $nom ), TEXT_DOMAIN ),
            'new_item_name'     => __( 'New ' . ucfirst( $nom ) . ' Name', TEXT_DOMAIN ),
            'menu_name'         => __( ucfirst( $nom ), TEXT_DOMAIN ),
        );

        $args = array(
            'hierarchical'      => $this->get_arg_config( 'hierarchical' ),
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' =>  $slug ),
            'capabilities'      => array(
				'manage_terms' => 'manage_options', // seul admin peut jouer avec les tags
				'edit_terms'   => 'manage_options',
				'delete_terms' => 'manage_options',
				'assign_terms' => 'manage_options'
            )
        );

        register_taxonomy( $slug, $this->get_arg_config( 'post_types' ), $args );
    }
}
?>
