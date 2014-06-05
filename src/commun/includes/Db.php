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

  }
?>
