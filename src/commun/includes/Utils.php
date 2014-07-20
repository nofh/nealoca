<?php
class Utils
{
     private static $_debug = true;
    /**
     * Retourne le nom complet du cpt
     *
     * Renvoi le nom du cpt mais sans verification si le cpt existe ou non !!
     * Simplement utile pour formater le nom des cpt
     *
     * @param string $nom nom court du cpt ( ex: organisme )
     *
     * @return string le nom complet du cpt ( ex: og_organisme_cpt )
     */
     public static function get_slug_cpt( $nom )
     {
         return PREFIX_PLUGIN . $nom . '_cpt';
     }

     public static function get_slug_taxo( $nom )
     {
         return strtolower( $nom );
     }

     public static function debug( $data )
     {
         if( self::$_debug )
         {
             if( is_array( $data ) || is_object( $data ) )
             {
                 echo("<script>console.log('PHP: ".json_encode( $data )."');</script>");
             } 
             else 
             {
                 echo("<script>console.log('PHP: ".$data."');</script>");
             }
         }
     }

     public static function get_attachement_id_by_url( $url )
     {
         $db = Db::get_instance();
         $attachment_id = $db->recuperer_attachement_id( $url );

        return $attachment_id;
     }
}
?>
