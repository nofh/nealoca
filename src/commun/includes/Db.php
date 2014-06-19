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
    }

    public function supprimer_element_menu( $nom )
    {
        $this->_db->delete( $this->_db->posts, array( 'post_title' => $nom , 'post_type' => 'nav_menu_item' ), array( '%s' ) );
        $this->debug();
    }

    public function recuperer_localisations( $post_id )
    {
        $post_localisation = new CustomPostTypeApi( $post_id, Utils::get_slug_cpt( 'localisation' ) );

        return $post_localisation;
    }

    public function debug()
    {
        var_dump( $this->_db->last_query );
    }
}
?>
