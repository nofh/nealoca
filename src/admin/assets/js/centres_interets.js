jQuery( function() {
    // recuperer le nb
    var cis_nb = jQuery( '#cis_nb' ).val();

    var ids = [ 'ajouter_ci' ];
    jQuery( '#ajouter_ci' ).click(function( event ) {
        cis_nb = ( cis_nb == undefined ) ? 0 : cis_nb;
        console.log( cis_nb );

        // index
        var index_ci_label = 'ci_label_' + cis_nb;
        var index_ci_coord = 'ci_coord_' + cis_nb;

        // html
        var ci_label_coord_html = "<li><label for='"+index_ci_label+"'>Label :</label><input type='text' name='"+index_ci_label+"' id='"+index_ci_label+"' value=''><label for='"+index_ci_coord+"'>Coordonees :</label><input type='text' name='"+index_ci_coord+"' id='"+index_ci_coord+"' value=''></li>";

        // ajout du ci
        jQuery( '#liste_cis' ).append( ci_label_coord_html );

        // mise a jour
        cis_nb++;
        console.log( cis_nb );
        jQuery( '#cis_nb' ).val( cis_nb );
    });
});
