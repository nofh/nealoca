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
        $this->description_principale = get_post_meta( $this->ID, PREFIX_META . 'description_principale', true );
        $this->label_description_principale = __( "Description principal", TEXT_DOMAIN );

        $this->description_secondaire = get_post_meta( $this->ID, PREFIX_META . 'description_secondaire', true );
        $this->label_description_secondaire = __( "Description secondaire", TEXT_DOMAIN );

        $this->description_tertiaire = get_post_meta( $this->ID, PREFIX_META . 'description_tertiaire', true );
        $this->label_description_tertiaire = __( "Description tertiaire", TEXT_DOMAIN );

        //slogan
        $this->slogan = get_post_meta( $this->ID, PREFIX_META . 'slogan', true );
        $this->label_slogan = __( "slogan", TEXT_DOMAIN );

        // slider
        $this->explode_slider();

        // gallerie
        $this->explode_gallerie();

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
        $this->label_acces = get_post_meta( $this->ID, PREFIX_META . 'label_acces', true );

        $this->coord_arrivee =  get_post_meta( $this->ID, PREFIX_META . 'coord_arrivee', true );
        $this->label_arrivee =  get_post_meta( $this->ID, PREFIX_META . 'nom_arrivee', true );
        $this->adr_arrivee =  get_post_meta( $this->ID, PREFIX_META . 'adr_arrivee', true );

        $this->coord_depart_un =  get_post_meta( $this->ID, PREFIX_META . 'coord_depart_un', true );
        $this->label_depart_un =  get_post_meta( $this->ID, PREFIX_META . 'nom_depart_un', true );
        $this->adr_depart_un =  get_post_meta( $this->ID, PREFIX_META . 'adr_depart_un', true );

        $this->coord_depart_deux =  get_post_meta( $this->ID, PREFIX_META . 'coord_depart_deux', true );
        $this->label_depart_deux =  get_post_meta( $this->ID, PREFIX_META . 'nom_depart_deux', true );
        $this->adr_depart_deux =  get_post_meta( $this->ID, PREFIX_META . 'adr_depart_deux', true );

        $this->coord_depart_trois =  get_post_meta( $this->ID, PREFIX_META . 'coord_depart_trois', true );
        $this->label_depart_trois =  get_post_meta( $this->ID, PREFIX_META . 'nom_depart_trois', true );
        $this->adr_depart_trois =  get_post_meta( $this->ID, PREFIX_META . 'adr_depart_trois', true );

        $this->coord_depart_quatre =  get_post_meta( $this->ID, PREFIX_META . 'coord_depart_quatre', true );
        $this->label_depart_quatre =  get_post_meta( $this->ID, PREFIX_META . 'nom_depart_quatre', true );
        $this->adr_depart_quatre =  get_post_meta( $this->ID, PREFIX_META . 'adr_depart_quatre', true );

        // region
        $this->region = get_post_meta( $this->ID, PREFIX_META . 'region', true );
        $this->label_region = __( "region", TEXT_DOMAIN );

        // villages
        $this->nombre_villages = get_post_meta( $this->ID, PREFIX_META . 'villages_nb', true );
        $this->formater_villages();

        $this->texte_villages = get_post_meta( $this->ID, PREFIX_META . 'texte_village', true );
        $this->label_texte_villages = __( 'Texte Village', TEXT_DOMAIN );

        // centres_interets
        $this->nombre_centres_interets = get_post_meta( $this->ID, PREFIX_META . 'cis_nb', true );
        $this->formater_centres_interets();

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

    // has
    public function has_centres_interets()
    {
        $ok = false;

        if( is_array( $this->centres_interets) )
        {
            if( count( $this->centres_interets ) > 0 ) 
            {

                $ok = true;
            }
        }

        return $ok;
    }

    // explode
    private function explode_slider()
    {
        $this->slider = array();
        $slider = get_post_meta( $this->ID, PREFIX_META . 'slider', true );

        if( $slider )
        {
            $this->slider = explode( ';', $slider );
        }
    }

    private function explode_gallerie()
    {
        $this->gallerie = array();
        $gallerie = get_post_meta( $this->ID, PREFIX_META . 'gallerie', true );

        if( $gallerie )
        {
            $this->gallerie = explode( ';', $gallerie );
        }
    }

    // formater
    public function formater_centres_interets()
    {
        $this->centres_interets = array();
        $restaurant = array();
        $boulangerie = array();

        for( $i = 0; $i < $this->nombre_centres_interets; $i++ )
        {
            $nom_var_label = 'label_centre_interet_' . $i;
            $nom_var_coord = 'coord_centre_interet_' . $i;
            $nom_var_categorie = 'categorie_centre_interet_' . $i;

            $this->$nom_var_label = get_post_meta( $this->ID, PREFIX_META . 'ci_label_' . $i, true );
            $this->$nom_var_coord = get_post_meta( $this->ID, PREFIX_META . 'ci_coord_' . $i, true );
            $this->$nom_var_categorie = get_post_meta( $this->ID, PREFIX_META . 'ci_categorie_' . $i, true );

            // creation du tab
            switch( $this->$nom_var_categorie )
            {
            case "restaurant":
                $restaurant[$this->$nom_var_label] = $this->$nom_var_coord;
                break;
            case "boulangerie":
                $boulangerie[$this->$nom_var_label] = $this->$nom_var_coord;
                break;
            }
        }

        $this->centres_interets['restaurant'] = $restaurant;
        $this->centres_interets['boulangerie'] = $boulangerie;

        $this->label_centres_interets = __( "centres_interets", TEXT_DOMAIN );

    }

     public function formater_villages()
    {
        $this->villages = array();

        for( $i = 0; $i < $this->nombre_villages; $i++ )
        {
            $nom_var_label = 'label_village_' . $i;
            $nom_var_coord = 'coord_village_' . $i;

            $this->$nom_var_label = get_post_meta( $this->ID, PREFIX_META . 'village_label_' . $i, true );
            $this->$nom_var_coord = get_post_meta( $this->ID, PREFIX_META . 'village_coord_' . $i, true );

            $this->villages[$this->$nom_var_label] = $this->$nom_var_coord;

        }

        $this->label_villages = __( "villages", TEXT_DOMAIN );
    }

}
?>
