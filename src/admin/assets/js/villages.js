jQuery( function() {
    // recuperer le nb
    var villages_nb = jQuery( '#villages_nb' ).val();

    var ids = [ 'ajouter_village' ];
    jQuery( '#ajouter_village' ).click(function( event ) {
        villages_nb = ( villages_nb == undefined ) ? 0 : villages_nb;
        console.log( villages_nb );

        // index
        var index_village_label = 'village_label_' + villages_nb;
        var index_village_coord = 'village_coord_' + villages_nb;
        var index_village_categorie = 'village_categorie_' + villages_nb;

        // html
        var village_label_coord_html = "<li><label for='"+index_village_label+"'>Label :</label><input type='text' name='"+index_village_label+"' id='"+index_village_label+"' value=''><label for='"+index_village_coord+"'>Coordonees :</label><input type='text' name='"+index_village_coord+"' id='"+index_village_coord+"' value=''> </li>";

        // ajout du village
        jQuery( '#liste_villages' ).append( village_label_coord_html );

        // mise a jour
        villages_nb++;
        console.log( villages_nb );
        jQuery( '#villages_nb' ).val( villages_nb );
    });
});
