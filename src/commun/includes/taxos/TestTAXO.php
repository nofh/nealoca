<?php
class TestTaxo extends Taxonomie
{
    public function __construct()
    {
        $config = array( 'nom' => TAXO_TEST, 'post_types' => array( Utils::get_nom_cpt( 'un' ) ) );

        parent::__construct( $config );
    }
}
?>
