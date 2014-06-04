<?php 
class TestSHC extends ShortCode
{
    public function __construct()
    {
        $config = $this->init_config();
        $fcts = $this->init_fcts();

        parent::__construct( $config, $fcts);
    }
    
    private function init_config()
    {
        $ok = array( 'nom' => 'fmwp' );
        return $ok;
    }

    private function init_fcts()
    {
        $test = array( 'nom' => 'test', 'categorie' => 'recuperation', 'args' => array( 1, 2, 3 ) );
        $fcts = array( $test );
        return $fcts;
    }

    public function test( $args=null )
    {
        print_r( $args );

        $labels = array( 'test_db' =>  'Test Db' );
        $valeurs = array( 'test_db' => 1 );
        $donnees = array( 'labels' => $labels, 'valeurs' => $valeurs, 'template' => 'test' );

        return $donnees;
    }


}
