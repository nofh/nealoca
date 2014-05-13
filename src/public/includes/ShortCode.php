<?php
class ShortCode
{
    private $_config;
    private $_toutes_donnees;


    public function __construct( $config, $fcts )
    {
        $this->setConfig( $config );
        $this->setFcts( $fcts );

        // registre la shortcode
        $tmp = $this->getConfig( 'callback' );
        $callback = ( ! $tmp ) ? 'shortcode' : $tmp;

        add_shortcode( $this->getConfig( 'nom' ), array( $this, $callback . '_callback' ) );
    }
  
    public function shortcode_callback( $atts_user ) // la callback par defaut
    {
        // args
        $atts_shc = $this->getConfig( 'atts_shc' );
        if( $atts_shc )
        {
            extract( shortcode_atts( $atts_shc, $atts_user, $this->getConfig( 'nom' ) ) );
        }

        // recuperer les infos
        $donnees = $this->recuperer_donnees();

        // creer afficahge via templates et infos 
        $affichage = $this->creer_affichage( $donnees );

        // terminer et echo le shortcode proprement
        return $affichage;
    }

    // comportement par defaut du shortcode ( determiner par la callbalck shortcode_callback )
    public function recuperer_donnees()
    {
        $donnees_toutes = array();
        $fcts_recuperation = $this->getFcts( 'recuperation' );
        foreach( $fcts_recuperation as $fct )
        {
            $nom_fct = $fct['nom'];
            $arg_fct = $fct['args'];
            $donnees = $this->$nom_fct( $arg_fct );

            if( $this->donnees_est_valide( $donnees ) )
            {
                $donnees_toutes[] = $donnees;
            }
            // log ou throw erreur 
        }

        return $donnees_toutes;
    }

    public function creer_affichage( $donnees_toutes )
    {
        $contenu_html = '';
        if( $donnees_toutes )
        {
            ob_start();
            foreach( $donnees_toutes as $template => $donnees )
            {
                if( ! empty( $donnees) )
                { 
                    // recuperation des labels
                    $labels = $donnees['labels'];

                    // recuperation des valeurs
                    $valeurs = $donnees['valeurs'];

                    // template d'affichage
                    $nom_shc = $this->getConfig( 'nom' );
                    $template = ( empty( $donnees['template'] ) ) ? $nom_shc : $donnees['template'];
                    $template = "content-shortcode_{$nom_shc}_{$template}.php";

                    // affichage
                    include EMP_PUBLIC_VIEWS . $template;
                }
            }
            $contenu_html = ob_get_contents();
            ob_end_clean();
        }

        return $contenu_html;
    }

    // getteurs setteurs 
   private function setConfig( $config )
    {
        if( is_array( $config ) )
        {
            $this->_config = $config;
        }
        else
        {
            $this->_config = false;
            echo "Erreur l'arg doit etre un array ";
            // thrown erreur
        }

        return $this->_config;
    }

    private function setFcts( $fcts )
    {
        if( is_array( $fcts ) )
        {
            $this->_fcts = $fcts;
        }
        else
        {
            $this->_fcts = false;
            // thrown erreur
        }

        return $this->_fcts;
    }

    public function donnees_est_valide( $donnees )
    {
        $ok = false;

        if( is_array( $donnees ) )
        {
            $ok = true;
        }

        return $ok;
    }

    public function getConfig( $nom )
    {
        $ok = false;
        if( array_key_exists( $nom, $this->_config ) )
        {
            $tmp = $this->_config[$nom];
            if( isset( $tmp ) && ! empty( $tmp ) )
            {
                $ok = $tmp;
            }
        }

        return $ok;
    }

    public function getFct( $nom )
    {
        $ok = false;
        foreach( $this->_fcts as $fct )
        {
                if( $fct['nom'] == $nom )
                {
                    $ok = $fct;
                }
        }

        return $ok;
    }

    public function getFcts( $categorie )
    {
        $ok = array();
        foreach( $this->_fcts as $fct )
        {
            if( $fct['categorie'] == $categorie )
            {
                $ok[] = $fct;
            }
        }
        return $ok;
    }

    public function getAtt( $nom )
    {
        $ok = false;
        $atts_sch = $this->getConfig( 'atts_shc' );
        if( $atts_sch )
        {
            if( array_key_exists( $nom, $atts_sch ) )
            {
                if( isset( $atts_shc[$nom] ) && ! empty( $atts_shc[$nom] ) )
                {
                    $ok = $atts_shc[$nom];
                }
            }
        }

        return $ok;
    }

    public function getAtts()
    {
        return  $this->getConfig( 'atts_shc' );
    }
}
?>
