<?php
class AjaxHandler
{
    private $_db;


    public function __construct()
    {

    }

    public function executer_query_callback() 
    {
        // db
        $this->_db = Db::get_instance();

        // recuperer l'info
        $type_requete = $_POST['type_requete']; 
        // creer requete
        $resultats = $this->executer_query( $type_requete );
        // retourner l'info
        die( json_encode( $resultats ) );
    }

    private function executer_query( $type_requete )
    {
        $type_requete = str_replace( '_shortcode', '', $type_requete );
        $arg = ( ! empty( $_POST['arg'] ) ) ? $_POST['arg'] : false;
        $args = ( ! empty( $_POST['args'] ) ) ? $_POST['args'] : false;

        $resultats = array();
        if( $type_requete == 'recuperer_localisation_acces' )
        {
            $resultats = $this->_db->recuperer_localisations('acces' );
        }
        else if( $type_requete == 'recuperer_infos_localisations' )
        {
            $resultats['items'] = $this->_db->recuperer_localisations(  $arg );
        }
        $count = count( $resultats['items'] );
        $resultats['requete'] = array( 'type_requete' => $type_requete, 'arg' => $arg, 'args' => $args, 'count' => $count );

        return $resultats;
    }
}
?>
