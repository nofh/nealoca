<?php
/**
 * indique si le post est un cpt organisme.
 *
 * utilise le post courant ou le post donner en argument.
 *
 * @param post $post null ou le post wp
 *
 * @return false|string false si n'est pas un cpt organisme ou renvoi le nom organisme.
 */
function is_cpt_accueil( $post=null )
{
    return is_cpt( 'accueil', $post );
}

function is_cpt_localisation( $post = null )
{
    return is_cpt( 'localisation', $post );
}

function is_cpt_appartement( $post = null )
{
    return is_cpt( 'appartement', $post );
}

function is_cpt_activite( $post = null )
{
    return is_cpt( 'activite', $post );
}

function is_cpt_contact( $post = null )
{
    return is_cpt( 'contact', $post );
}

/**
 * verifie si le post est un cpt.
 *
 * utilise le post courant ou celui donner en argument.
 * 
 * @param string $nom le nom simple du cpt ( organisme )
 * @param post $post le post wp ou null
 *
 * @return false|string false si n'est pas un cpt demander ou renvoi le nom du cpt
 *
 */
function is_cpt( $nom, $post_id = null )
{
    $ok = false;

    global $post;
    $post_id = ( $post_id == null ) ? $post->ID : $post_id;

    $post_type = get_post_type( $post_id );

    if( $post_type == Utils::get_slug_cpt( $nom ) ) // coorespond au cpt demander
    {
        $post_cpt = 'post_' . $nom; // creation d'un nom de variable ( post_ et la valeur contenu ds nom )
        $$post_cpt = new CustomPostTypeApi( $post_id, $post_type ); 

       $GLOBALS[$post_cpt] = $$post_cpt;

        $ok = $nom;   
    }

    return $ok;
}

/**
 * Possede toute les infos d'un cpt.
 *
 * Facilite l'acces aux infos d'un custom post type.
 * certain attributs ne sont present que si le cpt est organisme ou contact ou autres
 * ou si organisme et etic ( ex seo n'existe que pour un cpt organisme etic 
 * Chaque champ possede aussi un label pouvant etre traduit( __( 'texte', TEXT_DOMAINE' )
 * 
 * @property string $ID id du post;
 * @property string $label_id le label de l'id ( idem pour les autres champs )
 * @property string $post_type le post type du post
 * @property string $id_organisme l'id organisme
 * @property string $date_creation_organisme date de creation de l'organisme
 * @property string $numero_entreprise numero de l'entreprise
 * @property string $nom_organisme nom de l'entreprise
 * @property string $description_organisme description de l'entreprise
 * @property string $logo le logo de l'entreprise
 * @property string $site_web le site web de l'entreprise
 * @property string $adresse_organisme l'adresse de l'entreprise sous forme de string rue;numero;boite;cp;localtie ( utiliser explode ( ',' , $adresse ) )
 * @property string $rue rue de l'adresse de l'organisme
 * @property string $numero le numero de l'adresse de l'organisme
 * @property string $bt la boite de l'adresse de l'organimse
 * @property string $boite alias sur bt
 * @property string $cp le cp de l'organisme 
 * @property string $code_postal alias sur cp
 * @property string $localite localite de l'organisme ( ex: namur )
 * @property string $pays_organisme 
 * @property string $lat_long_organisme 
 * @property string $tel_organisme 
 * @property string $fax_organisme 
 * @property string $nom_contact 
 * @property string $prenom_contact 
 * @property string $email_contact 
 * @property string $numero_etic 
 * @property string $date_souscription 
 * @property string $seo 
 * @property string $ec 
 * @property string $erp 
 * @property string $em 
 * @property string $ma certification mobile application 
 * @property string $fr 
 * @property string $label_seo labe seo
 * @property string $label_EC label ecommerce
 * @property string $label_ERP label erp
 * @property string $label_EM label emarketing
 * @property string $label_MA label applications mobile 
 * @property string $label_FR 
 * @property string $date_creation 
 * @property string $pays 
 * @property string $lat_long 
 * @property string $telephone 
 * @property string $tel 
 * @property string $fax 
 * @property string $nom 
 * @property string $prenom 
 * @property string $email 
 * @property string $title 
 * @property string $content 
 * @property string $description 
 *
 */
class CustomPostTypeApi
{


    /**
     * un constructor est un constructor...
     *
     * creer des attributs en rapports avec le contenu du cpt.
     * execute une sous methode pour ce fair.
     * 
     * @param string $id l'id du post dont on veut les infos.
     * @param string $post_type le type du post ( sous la forme ex: og_organisme_cpt -> Utils::get_type_cpt( 'organimse' ) fournit cette info.
     * 
     * @return none
     */
    public function __construct( $id, $post_type )
    {
        $this->init( $id, $post_type );
    }

    /**
     * Choisit la bonne methode d'initialisation a utiliser.
     *
     * se base sur le post_type
     *
     * @param string $id l'id du post dont on veut les infos.
     * @param string $post_type le type du post ( sous la forme ex: og_organisme_cpt -> Utils::get_type_cpt( 'organimse' ) fournit cette info.
     *
     * @return none
     */
    private function init( $id, $post_type )
    {
        switch( $post_type )
        {
        case Utils::get_slug_cpt( 'accueil' ):
            $this->init_accueil($id, $post_type );
            break;
        case Utils::get_slug_cpt( 'localisation' ):
            $this->init_localisation( $id, $post_type );
            break;
        case Utils::get_slug_cpt( 'appartement' ):
            $this->init_appartement( $id, $post_type );
            break;
        case Utils::get_slug_cpt( 'activite' ):
            $this->init_activite( $id, $post_type );
            break;
        case Utils::get_slug_cpt( 'contact' ):
            $this->init_contact( $id, $post_type );
            break;
        }
    }

    public function init_accueil( $id, $post_type )
    {
        $this->ID = $id;
        $this->label_id = __( 'ID: ', TEXT_DOMAIN );

        $this->post_type = $post_type;
        $this->label_post_type = __( 'Post type : ', TEXT_DOMAIN );

        $this->permalink = get_permalink( $this->ID );
        $this->label_permalink = __( 'Permalink', TEXT_DOMAIN );

        $this->content =  get_post_field( 'post_content', $this->ID );
        $this->label_content = __( "Contenu principal", TEXT_DOMAIN );

        // descriptions
        $this->description_principale = $this->content;
        $this->label_description_principale = __( "Description principal", TEXT_DOMAIN );

        $this->description_secondaire = get_post_meta( $this->ID, PREFIX_META . 'description_secondaire', true );
        $this->label_description_secondaire = __( "Description secondaire", TEXT_DOMAIN );

        $this->description_tertiaire = get_post_meta( $this->ID, PREFIX_META . 'description_tertiaire', true );
        $this->label_description_tertiaire = __( "Description tertiaire", TEXT_DOMAIN );

        //slogan
        $this->slogan = get_post_meta( $this->ID, PREFIX_META . 'slogan', true );
        $this->label_slogan = __( "slogan", TEXT_DOMAIN );

        // galleries
        $this->galleries = get_post_galleries( $this->ID, false );
        $this->explode_slider();

    }

    public function init_localisation( $id, $post_type )
    { 
        $this->ID = $id;
        $this->label_id = __( 'ID: ', TEXT_DOMAIN );

        $this->post_type = $post_type;
        $this->label_post_type = __( 'Post type : ', TEXT_DOMAIN );

        $this->permalink = get_permalink( $this->ID );
        $this->label_permalink = __( 'Permalink', TEXT_DOMAIN );

        $this->content =  get_post_field( 'post_content', $this->ID );
        $this->label_content = __( "Contenu principal", TEXT_DOMAIN );
        
        // acces
        $this->acces = get_post_meta( $this->ID, PREFIX_META . 'acces', true );
        $this->label_acces = __( "acces", TEXT_DOMAIN );

        // region
        $this->region = get_post_meta( $this->ID, PREFIX_META . 'region', true );
        $this->label_region = __( "region", TEXT_DOMAIN );

        // villages
        $this->villages = get_post_meta( $this->ID, PREFIX_META . 'villages', true );
        $this->label_villages = __( "villages", TEXT_DOMAIN );

        // centres_interets
        $this->centres_interets = get_post_meta( $this->ID, PREFIX_META . 'centres_interets', true );
        $this->label_centres_interets = __( "centres_interets", TEXT_DOMAIN );
    }

    public function init_appartement( $id, $post_type )
    {
        $this->ID = $id;
        $this->label_id = __( 'ID: ', TEXT_DOMAIN );

        $this->post_type = $post_type;
        $this->label_post_type = __( 'Post type : ', TEXT_DOMAIN );

        $this->permalink = get_permalink( $this->ID );
        $this->label_permalink = __( 'Permalink', TEXT_DOMAIN );

        $this->content =  get_post_field( 'post_content', $this->ID );
        $this->label_content = __( "Contenu principal", TEXT_DOMAIN );

        // description
        $this->description = get_post_meta( $this->ID, PREFIX_META . 'description', true );
        $this->label_description = __( "description", TEXT_DOMAIN );

        // commodites
        $this->commodites = get_post_meta( $this->ID, PREFIX_META . 'commodites', true );
        $this->label_commodites = __( "commodites", TEXT_DOMAIN );

        // photos
        $this->photos = get_post_meta( $this->ID, PREFIX_META . 'photos', true );
        $this->label_photos = __( "photos", TEXT_DOMAIN );
    }

    public function init_activite( $id, $post_type )
    {
        $this->ID = $id;
        $this->label_id = __( 'ID: ', TEXT_DOMAIN );

        $this->post_type = $post_type;
        $this->label_post_type = __( 'Post type : ', TEXT_DOMAIN );

        $this->permalink = get_permalink( $this->ID );
        $this->label_permalink = __( 'Permalink', TEXT_DOMAIN );

        $this->content =  get_post_field( 'post_content', $this->ID );
        $this->label_content = __( "Contenu principal", TEXT_DOMAIN );
    }

    public function init_contact( $id, $post_type )
    {
        $this->ID = $id;
        $this->label_id = __( 'ID: ', TEXT_DOMAIN );

        $this->post_type = $post_type;
        $this->label_post_type = __( 'Post type : ', TEXT_DOMAIN );

        $this->permalink = get_permalink( $this->ID );
        $this->label_permalink = __( 'Permalink', TEXT_DOMAIN );

        $this->content =  get_post_field( 'post_content', $this->ID );
        $this->label_content = __( "Contenu principal", TEXT_DOMAIN );
    }

    public function has_logo()
    {
        $ok = false;

        if( isset( $this->logo ) && ! empty( $this->logo ) )
        {
            $ok = true;
        }

        return $ok;
    }


    public function is_etic()
    {
       // duplicate avec Utils est etic  
        return has_term( Utils::get_nom_tag( ID_TAG_ETIC ), TAXO_TAG, $this->ID );
    }

    private function explode_slider()
    {
        $this->slider = array();
        $tmp = ( array_key_exists( 0, $this->galleries ) ) ? $this->galleries[0]['ids'] : false;
        if( $tmp )
        {
            $this->slider = explode( ',', $tmp );
        }
    }

    /**
     * transforme la chaine de reseaux sociaux ( issut de la db )
     *
     * cree des variables d'instance pour chaque reseaux ex $this->facebook ayant pour valeur le liens fb
     * cree en mm temps un label $this->label_facebook -> facebook
     * modifie $this->reseaux_sociaux pour en fair un tableau associatif array( 'facebook' => 'lien', 'twitter' => 'lien' );
     * 
     * @return none
     */
    private function explode_reseaux_sociaux()
    {
        if( isset( $this->reseaux_sociaux ) && ! empty( $this->reseaux_sociaux ) )
        {
            $elements_resaux = explode( ';', $this->reseaux_sociaux );

            //facebook,https://www.facebook.com/ConnexionCoworking;twitter,https://twitter.com/coworkingmons
            //elements 
            $tmp_reseaux_sociaux = array();
            foreach( $elements_resaux as $element_resau )
            {
                $nom_et_lien = explode( ',', $element_resau );

                $nom = ( array_key_exists( 0, $nom_et_lien ) ) ? $nom_et_lien[0] : '';
                $lien = ( array_key_exists( 1, $nom_et_lien ) ) ? $nom_et_lien[1] : '';

                $tmp_reseaux_sociaux[$nom] = $lien;

                // aussi en variable du genre $this->facebook --> le lien 
                $this->$nom = $lien;
                $label = 'label_' . $nom;
                $this->$label = $nom; // label du reseau ici on utiliser betement le mm nom ( issut de la db )
            }

        // reformater reseaux
        $this->reseaux_sociaux = $tmp_reseaux_sociaux;
        }
    }

    public function formater_adresse() // TODO doit formater  adresse organsime
    {
        $adresse_formater = '';
        $elements = explode( ';', $this->adresse_organisme );
        for( $i = 0; $i < count( $elements ); $i++)
        {
            $sep = ' ';
            if( $i == 2 )
            {
                $sep = '<br>';
            }
            else if( $i == 3 )
            {
                $sep = ' -- ';
            }
            $element = ( ! empty( $elements[$i] ) ) ? $elements[$i] : '';
            $adresse_formater .= $element . $sep;
        }

            return $adresse_formater;
    }


}
?>
