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
        case Utils::get_slug_cpt( 'contact' ):
            $this->init_contact( $id, $post_type );
            break;
        }
    }

    public function init_accueil( $id, $post_type )
    {
        $this->ID = $id;
        $this->post_type = $post_type;

        $this->langue = get_the_terms( $this->ID, TAXO_LANGUE );

    }
    /**
     * Cree les attributs coorepsondant aux valeurs du cpt organisme.
     *
     * recupere toutes les valeurs du cpt organsime dont l'id est donner en arg
     * plusieurs attributs peuvent avoir la mm valeur, c'est des alias pous se simplifier la vie
     *
     * @param string $id l'id du post dont on veut les infos.
     * @param string $post_type le type du post ( sous la forme ex: og_organisme_cpt -> Utils::get_type_cpt( 'organimse' ) fournit cette info.
     *
     * @return none
     */
    private function init_organisme( $id, $post_type )
    {
        $this->ID = $id;
        $this->label_id = __( 'ID: ', TEXT_DOMAIN );

        $this->post_type = $post_type;
        $this->label_post_type = __( 'Post type : ', TEXT_DOMAIN );

        $this->permalink = get_permalink( $this->ID );
        $this->label_permalink = __( 'Permalink', TEXT_DOMAIN );

        $this->tags = get_the_term_list( $this->ID, TAXO_TAG, '', '', '' );// get_terms( TAXO_TAG );
        $this->label_tags = __( 'Tags', TEXT_DOMAIN );

        // id, date creation, numero entreprise
        $this->id_organisme = get_post_meta( $this->ID, PREFIX_META . 'id_organisme', true );
        $this->label_id_organisme = __( 'Id organisme', TEXT_DOMAIN );

        $this->date_creation_organisme = get_post_meta( $this->ID, PREFIX_META . 'date_creation_organisme', true );
        $this->label_date_creation_organisme = __( "Date de création", TEXT_DOMAIN );

        $this->numero_entreprise = get_post_meta( $this->ID, PREFIX_META . 'numero_entreprise', true );
        $this->label_numero_entreprise = __( "Numéro d'entreprise", TEXT_DOMAIN );

        // logo et site web
        $this->nom_organisme = get_the_title( $this->ID );
        $this->label_nom_organisme = __( "Nom de l'organisme", TEXT_DOMAIN );

        $this->description_organisme =  get_post_field( 'post_content', $this->ID );
        $this->label_description_organisme = __( "Description", TEXT_DOMAIN );

        $this->logo = get_post_meta( $this->ID, PREFIX_META . 'logo', true );
        $this->label_logo = __( "Logo :", TEXT_DOMAIN );

        $this->site_web = get_post_meta( $this->ID, PREFIX_META . 'site_web', true );
        $this->label_site_web = __( "Site Web", TEXT_DOMAIN );

        // adresse
        $this->adresse_organisme = get_post_meta( $this->ID, PREFIX_META . 'adresse_organisme', true );
        $this->label_adresse_organisme = __( "Adresse", TEXT_DOMAIN );
        $this->explode_adresse( $this->adresse_organisme );

        $this->pays_organisme = get_post_meta( $this->ID, PREFIX_META . 'pays_organisme', true );
        $this->label_pays_organisme = __( "Pays", TEXT_DOMAIN );

        $this->lat_long_organisme = get_post_meta( $this->ID, PREFIX_META . 'lat_long_organisme', true );
        $this->label_lat_long_organisme = __( "Latitude Longitude", TEXT_DOMAIN );

        // tel, fax
        $this->tel_organisme = get_post_meta( $this->ID, PREFIX_META . 'tel_organisme', true );
        $this->label_tel_organisme = __( "Téléphone", TEXT_DOMAIN );

        $this->fax_organisme = get_post_meta( $this->ID, PREFIX_META . 'fax_organisme', true );
        $this->label_fax_organisme = __( "Fax", TEXT_DOMAIN );

        // reseaux sociaux
        $this->reseaux_sociaux = get_post_meta( $this->ID, PREFIX_META . 'reseaux_sociaux', true );
        $this->label_reseaux_sociaux = __( "Réseaux Sociaux", TEXT_DOMAIN );
        $this->explode_reseaux_sociaux();

        // contact
        $this->nom_contact = get_post_meta( $this->ID, PREFIX_META . 'nom_contact', true );
        $this->label_nom_contact = __( "Nom", TEXT_DOMAIN );

        $this->prenom_contact = get_post_meta( $this->ID, PREFIX_META . 'prenom_contact', true );
        $this->label_prenom_contact = __( "Prenom", TEXT_DOMAIN );

        $this->email_contact = get_post_meta( $this->ID, PREFIX_META . 'email_contact', true );
        $this->label_email_contact = __( "Email", TEXT_DOMAIN );
       
        // etic
        $this->numero_etic = get_post_meta( $this->ID, PREFIX_META . 'numero_etic', true );
        $this->label_numero_etic = __( "Numéro Etic", TEXT_DOMAIN );

        $this->date_souscription = get_post_meta( $this->ID, PREFIX_META . 'date_souscription', true );
        $this->label_date_souscription = __( "Date Souscription", TEXT_DOMAIN );

        $this->seo = get_post_meta( $this->ID, PREFIX_META . 'seo', true );
        $this->label_seo = LABEL_SEO;
        $this->seo_active = ( $this->seo == 1 ) ? $this->label_seo : '';

        $this->ec = get_post_meta( $this->ID, PREFIX_META . 'ec', true );
        $this->label_ec = LABEL_EC;
        $this->ec_active = ( $this->ec == 1 ) ? $this->label_ec : '';

        $this->erp = get_post_meta( $this->ID, PREFIX_META . 'erp', true );
        $this->label_erp = LABEL_ERP;
        $this->erp_active = ( $this->erp == 1 ) ? $this->label_erp : '';

        $this->em = get_post_meta( $this->ID, PREFIX_META . 'em', true );
        $this->label_em = LABEL_EM;
        $this->em_active = ( $this->em == 1 ) ? $this->label_em : '';

        $this->ma = get_post_meta( $this->ID, PREFIX_META . 'ma', true );
        $this->label_ma = LABEL_MA;
        $this->ma_active = ( $this->ma == 1 ) ? $this->label_ma : '';

        $this->fr = get_post_meta( $this->ID, PREFIX_META . 'fr', true );
        $this->label_fr =  LABEL_FR;
        $this->fr_active = ( $this->fr == 1 ) ? $this->label_fr : '';
        

        // pour se simplifier la vie // TODO ajouter les lables pour les alias 
        $this->date_creation = $this->date_creation_organisme;
        $this->pays = $this->pays_organisme;
        $this->lat_long = $this->lat_long_organisme;
        $this->telephone = $this->tel_organisme;
        $this->tel = $this->tel_organisme;
        $this->fax = $this->fax_organisme;
        $this->nom = $this->nom_contact;
        $this->prenom = $this->prenom_contact;
        $this->email = $this->email_contact;
        $this->title = $this->nom_organisme;
        $this->content = $this->description_organisme;
        $this->description = $this->description_organisme;

        // alias label
        $this->label_date_creation = $this->label_date_creation_organisme;
        $this->label_telephone = $this->label_tel_organisme;
        $this->label_fax = $this->label_fax_organisme;
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

    public function has_reseaux_sociaux()
    {
        $ok = false;

        if( isset( $this->reseaux_sociaux ) && ! empty( $this->reseaux_sociaux ) )
        {
            $ok = true;
        }

        return $ok;
    }

    public function has_telephone()
    {
        $ok = false;

        if( isset( $this->telephone ) && ! empty( $this->telephone ) )
        {
            $ok = true;
        }

        return $ok;
    }

    public function has_fax()
    {
        $ok = false;

        if( isset( $this->fax ) && ! empty( $this->fax ) )
        {
            $ok = true;
        }

        return $ok;
    }

    public function has_mail_contact()
    {
        $ok = false;

        if( isset( $this->mail_contact ) && ! empty( $this->mail_contact ) )
        {
            $ok = true;
        }

        return $ok;
    }

    public function has_seo_active()
    {
        $ok = false;

        if( isset( $this->seo ) && ! empty( $this->seo ) )
        {
            if( $this->seo == 1 )
            {
                $ok = true;
            }
        }

        return $ok;
    }

    public function has_erp_active()
    {
        $ok = false;

        if( isset( $this->erp ) && ! empty( $this->erp ) )
        {
            if( $this->erp == 1 )
            {
                $ok = true;
            }
        }

        return $ok;
    }

    public function has_ec_active()
    {
        $ok = false;

        if( isset( $this->ec ) && ! empty( $this->ec ) )
        {
            if( $this->ec == 1 )
            {
                $ok = true;
            }
        }

        return $ok;
    }

    public function has_em_active()
    {
        $ok = false;

        if( isset( $this->em ) && ! empty( $this->em ) )
        {
            if( $this->em == 1 )
            {
                $ok = true;
            }
        }

        return $ok;
    }

    public function has_ma_active()
    {
        $ok = false;

        if( isset( $this->ma ) && ! empty( $this->ma ) )
        {
            if( $this->ma == 1 )
            {
                $ok = true;
            }
        }

        return $ok;
    }

    public function has_fr_active()
    {
        global $certificationFr;
        $ok = false;

        if( isset( $this->fr ) && ! empty( $this->fr ) )
        {
            if( $this->fr == 1 && $certificationFr == 'yes' )
            {
                $ok = true;
            }
        }

        return $ok;
    }

    public function is_etic()
    {
       // duplicate avec Utils est etic  
        return has_term( Utils::get_nom_tag( ID_TAG_ETIC ), TAXO_TAG, $this->ID );
    }
    /**
     * explode l'adresse.
     *
     * cree les attributs pour chaque elements de l'adresse ( rue, numero, bt, cp, localite )
     *
     * @return none
     */
    private function explode_adresse()
    {
        $elements_adresse = explode( ';', $this->adresse_organisme );

        // elemennts
        $this->rue = ( array_key_exists( 0, $elements_adresse) ) ? $elements_adresse[0] : '';
        $this->numero= ( array_key_exists( 1, $elements_adresse ) ) ? $elements_adresse[1] : '';
        $this->bt = ( array_key_exists( 2, $elements_adresse ) )? $elements_adresse[2] : '';
        $this->cp = ( array_key_exists( 3, $elements_adresse ) ) ?$elements_adresse[3] : '';
        $this->localite = ( array_key_exists( 4, $elements_adresse ) ) ? $elements_adresse[4] : '';

        // alias
        $this->boite = $this->bt;
        $this->code_poste = $this->cp;
        $this->localite_organisme = $this->localite;
//TODO reforater adresse
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
