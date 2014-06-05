<?php
class Db
{
    private static $_instance = null;
    private $_db;


    private function __construct()
    {
        global $wpdb;
        $this->_db = $wpdb;
    }

    public static function get_instance()
    {
        if( self::$_instance == null ) 
        {
            self::$_instance = new self;
        }

		return self::$_instance;
    }

    public function supprimer_menu( $nom )
    {
        $this->_db->delete( $this->_db->terms, array( 'name' => $nom ), array( '%s' ) );

        //$this->_db->delete( 'nl_terms', array( 'name' => $nom ) );
    }

    public function recuperer_ids_organismes( $tag=NOM_TAG_ETIC, $order_by='post_title', $ordre_trie='ASC', $certification_active='toute' )
    {
        $ids = array();

        $jointure_certification_etic = '';
        if( $tag == NOM_TAG_ETIC )
        {
            if( $certification_active != 'toute' )
            {
                $champ_db_certification = PREFIX_META . $certification_active;
                $jointure_certification_etic =  "INNER JOIN ( SELECT post_id, meta_value FROM wp_postmeta WHERE meta_key = '${champ_db_certification}'  AND meta_value = '1' ) pm ON pm.post_id = p.ID ";
            }
        }

        // recuperation des ids 
        if( $order_by == 'post_title' )
        {
            $ids = $this->_db->get_col( $this->_db->prepare( 
                "SELECT DISTINCT p.id " .
                "FROM wp_posts p " .
                $jointure_certification_etic .
                "INNER JOIN wp_term_relationships tr ON tr.object_id = p.id " .
                "INNER JOIN wp_term_taxonomy t ON t.term_taxonomy_id = tr.term_taxonomy_id " .
                "INNER JOIN wp_terms ts ON ts.term_id = t.term_id " .
                "WHERE ts.name = '%s' " .
                "AND p.post_type = '%s' " .
                "ORDER BY p.post_title $ordre_trie ",  // si passe par %s de prepare alos wp protege la valeur par des ''
                $tag, Utils::get_type_cpt( 'organisme'), $ordre_trie )
            ); 
        }
        else // post meta
        {
            if( $order_by == 'date_souscription' )
            {
                $ids = $this->_db->get_col(  
                    "SELECT DISTINCT p.id ".
                    "FROM wp_posts p ".
                    $jointure_certification_etic .
                    "INNER JOIN ( SELECT post_id, meta_value FROM wp_postmeta WHERE meta_key = '_og_date_souscription' ) pmm ON pmm.post_id = p.ID ".
                    "INNER JOIN wp_term_relationships tr ON tr.object_id = p.id ".
                    "INNER JOIN wp_term_taxonomy t ON t.term_taxonomy_id = tr.term_taxonomy_id ".
                    "INNER JOIN wp_terms ts ON ts.term_id = t.term_id ".
                    "WHERE ts.name = 'charte_etic' ".
                    "AND p.post_type = 'og_organisme_cpt' ". 
                    "ORDER BY STR_TO_DATE( pmm.meta_value, '%d.%m.%Y' ) $ordre_trie "  // pas de prepares car pas d'echapement possible pour % qui est utiliser par prepare
                );
            }
            else if( $order_by == 'pays_organisme' )
            {
                $ids = $this->_db->get_col( $this->_db->prepare( 
                    "SELECT DISTINCT p.id " .
                    "FROM wp_posts p " .
                    $jointure_certification_etic .
                    "INNER JOIN ( SELECT post_id, meta_value FROM wp_postmeta WHERE meta_key = '%s' ) pmm ON pmm.post_id = p.ID " .
                    "INNER JOIN wp_term_relationships tr ON tr.object_id = p.id " .
                    "INNER JOIN wp_term_taxonomy t ON t.term_taxonomy_id = tr.term_taxonomy_id " .
                    "INNER JOIN wp_terms ts ON ts.term_id = t.term_id " .
                    "WHERE ts.name = '%s' " .
                    "AND p.post_type = '%s' " .
                    "ORDER BY pmm.meta_value $ordre_trie ",  // si passe par %s de prepare alos wp protege la valeur par des ''
                    PREFIX_META . $order_by, $tag, Utils::get_type_cpt( 'organisme'), $ordre_trie )
                ); 
            }
        }
       // var_dump( $this->_db->last_query ) ;


        return $ids;
    }
}
?>
