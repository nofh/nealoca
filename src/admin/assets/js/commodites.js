jQuery( function() {
    // recuperer le nb
    var commodites_nb = jQuery( '#commodites_nb' ).val();

    var ids = [ 'ajouter_commodite' ];
    jQuery( '#ajouter_commodite' ).click(function( event ) {
        commodites_nb = ( commodites_nb == undefined ) ? 0 : commodites_nb;
        console.log( commodites_nb );

        // index
        var index_commodite_label = 'commodite_label_' + commodites_nb;
        var index_commodite_quantite = 'commodite_quantite_' + commodites_nb;
        var index_commodite_categorie = 'commodite_categorie_' + commodites_nb;

        // html
        var commodite_html = "<li><label for='"+index_commodite_label+"'>Label :</label><input type='text' name='"+index_commodite_label+"' id='"+index_commodite_label+"' value=''><label for='"+index_commodite_quantite+"'>Quantités:</label><input type='text' name='"+index_commodite_quantite+"' id='"+index_commodite_quantite+"' value=''> </li>";

        // ajout du commodite
        jQuery( '#liste_commodites' ).append( commodite_html );

        // mise a jour
        commodites_nb++;
        console.log( commodites_nb );
        jQuery( '#commodites_nb' ).val( commodites_nb );
    });
});
