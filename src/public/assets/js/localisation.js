var map;

jQuery(document).ready(function(){
    if(  jQuery( '#args_localisation' ).length != 0 ){
        var id_map = jQuery( '#args_localisation' ).data( 'id_map' ) ;
        var post_id = jQuery( '#args_localisation' ).data( 'post_id' );

        // traitement
        if( post_id != undefined  && id_map != undefined  ) 
        {
            console.log( 'demande wp' );
            // demander a wp les infos 
            jQuery.post( ajaxurl, 
                {
                    'action': 'executer_query',
                    'type_requete' : 'recuperer_infos_localisations',
                    'arg' : post_id,
                    'args' : -1 
                }, 
                function( reponse)
                {
                    console.log( reponse );
                    var resultats_wp = jQuery.parseJSON( reponse );
                    var items = resultats_wp.items;
                    var type_map = resultats_wp.requete.args.type;

                    choix_actions( items );
                //    var nealoca_map = new NeaLocaMap( type_map, items );
                }, 'text');
 
        }
    }
});

function choix_actions( items )
{

    // par defaut
    creer_map_acces( items.coord_arrivee, items.label_arrivee, items.adr_arrivee, items.coord_depart_un, items.coord_depart_deux, items.coord_depart_trois, items.coord_depart_quatre, items.label_depart_un, items.label_depart_deux, items.label_depart_trois, items.label_depart_quatre );

    // par choix
    var ids = [ 'tab_acces', 'tab_region', 'tab_villages', 'tab_cis' ];
    for( i = 0; i < ids.length; i++){
        jQuery( '#' + ids[i] ).click( function( event ){
            switch( event.target.id ){
                case 'tab_acces':
                    creer_map_acces( items.coord_arrivee, items.label_arrivee, items.adr_arrivee, items.coord_depart_un, items.coord_depart_deux, items.coord_depart_trois, items.coord_depart_quatre, items.label_depart_un, items.label_depart_deux, items.label_depart_trois, items.label_depart_quatre );
                    break;
                case 'tab_region':
                    creer_map_region( items.coord_arrivee );
                    break;
                case 'tab_villages':
                    creer_map_villages( items.coord_arrivee, items.label_arrivee, items.adr_arrivee, items.villages );
                    break;
                case 'tab_cis':
                    creer_map_cis( items.centres_interets, items.coord_arrivee, items.label_arrivee, items.adr_arrivee );
                    break;
            }
        });
    }
}

function creer_map( coord_arrivee )
{
    var arrivee = coord_arrivee.split( ',' );

    map = new GMaps({
        el: '#map-canvas',
        lat: arrivee[0],
        lng: arrivee[1],
        zoom: 9,
        zoomControl : true,
        zoomControlOpt: {
            style : 'SMALL',
        position: 'TOP_LEFT'
        },
        panControl : false,
    });
}

function creer_map_acces( coord_arrivee, label_arrivee, adr_arrivee, coord_depart_un, coord_depart_deux, coord_depart_trois, coord_depart_quatre, label_depart_un, label_depart_deux, label_depart_trois, label_depart_quatre  ) // FIXME CRADE!!!
{ 
    creer_map( coord_arrivee );

    var ids = [ 'depart_un', 'depart_deux', 'depart_trois', 'depart_quatre' ];
    var coords = [ coord_depart_un, coord_depart_deux, coord_depart_trois, coord_depart_quatre ];
    var labels = [ label_depart_un, label_depart_deux, label_depart_trois, label_depart_quatre ];

    // transfo arrivee
    var coord = coord_arrivee.split( ',' );
    var lat_arrivee =  coord[0];
    var lng_arrivee =  coord[1];

    // par defaut 
    marquer_maison( coord_arrivee, label_arrivee, adr_arrivee )

    // choix user
    for( i in ids )
    {
        jQuery( '#' + ids[i] ).click( function(event){
            // recuperer les coords 
            var tmp_coord = '';
            var label = '';
            for( j in coords ){
                if( ids[j] == event.target.id ){
                    tmp_coord = coords[j];
                    label = labels[j];
                }
            }
            // transfo
            var coord = tmp_coord.split( ',' );
            var lat =  coord[0];
            var lng =  coord[1];
            
            // nettoyage 
            map.cleanRoute();
            map.removeMarkers();

            // cration
            map.drawRoute({
                origin: [lat, lng],
                destination: [lat_arrivee, lng_arrivee],
                travelMode: 'driving',
                strokeColor: '#131540',
                strokeOpacity: 0.6,
                strokeWeight: 6
            });

            // calculer la distance
            depart = [ lat, lng ];
            arrivee = [ lat_arrivee, lng_arrivee ];
            calculer_distances( depart, arrivee );

            // ajouter un markeur sur le point de depart 
            map.addMarker({
                lat: lat,
                lng: lng,
                title: label,
            });

            // marker maison
            marquer_maison( coord_arrivee, label_arrivee, adr_arrivee )

            // centrer la carte
            map.setCenter( lat, lng );


        });
    }
    
}

function calculer_distances( depart, arrivee )
{
    // transfo 
    var depart = new google.maps.LatLng( depart[0], depart[1]);
    var arrivee = new google.maps.LatLng( arrivee[0], arrivee[1]);
    
    //
     var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix(
    {
      origins: [depart],
      destinations: [arrivee],
      travelMode: google.maps.TravelMode.DRIVING,
      unitSystem: google.maps.UnitSystem.METRIC,
      avoidHighways: false,
      avoidTolls: false
    }, function(response, status){
        if (status != google.maps.DistanceMatrixStatus.OK){
            alert('Error was: ' + status);
        } 
        else {
            console.log( response );
            // preparation de l'info recuperer pour l'affichage
            var origins = response.originAddresses;
            var destinations = response.destinationAddresses;
            for (var i = 0; i < origins.length; i++) {
                var results = response.rows[i].elements;
                for (var j = 0; j < results.length; j++){

                    var element = results[j];
                    var distance = element.distance.text;
                    var duration = element.duration.text;
                    var from = origins[i];
                    var to = destinations[j];

                    var infos = [from, to, distance, duration];
                    // affichage
                    var elements = document.getElementById("distances").getElementsByTagName("tr");
                    for ( var k = 0; k < elements.length; k++)
                    {
                        var aRemplacer = elements.item(k).getElementsByTagName("td");
                        for ( var l = 0; l < aRemplacer.length; l++)
                        {
                            aRemplacer.item(l).innerHTML = infos[l];
                        }
                    }
                }
            }
        } 
    });
}

function creer_map_region( coord_arrivee )
{
    creer_map( coord_arrivee );
    map.setZoom( 9 );
        
    var path = init_path_region();

    polygon = map.drawPolygon({
        paths: path, // pre-defined polygon shape
            strokeColor: '#BBD8E9',
            strokeOpacity: 1,
            strokeWeight: 3,
            fillColor: '#BBD8E9',
            fillOpacity: 0.6
    });


}

function creer_map_villages( coord_arrivee, label_arrivee, adr_arrivee, villages )
{
    creer_map( coord_arrivee );
    map.setZoom( 12 );

    console.log( villages );
    for (var nom in villages) {
        var tmp_coord = villages[nom];
        console.log( coord );

        // transformation
        var coord = tmp_coord.split( ',' );
        var lat =  coord[0];
        var lng =  coord[1];


        // marker
        map.addMarker({
            lat: lat,
            lng: lng,
            title: nom,
        });
    }

    marquer_maison( coord_arrivee, label_arrivee, adr_arrivee );
}

function creer_map_cis( cis, coord_arrivee, label_arrivee, adr_arrivee )
{
    creer_map( coord_arrivee );
    map.setZoom( 16 );
    map.setCenter( 40.380983, 23.923349  );

    marquer_maison( coord_arrivee, label_arrivee, adr_arrivee );
    // ajout actions
    jQuery( '#cis > li'  ).each( function( index ){
        jQuery( this ).click( function( event ){
            
            // nettoyage
            map.removeMarkers();
            // recuperer la cat
            for (var categorie in cis ) {
                // n'affiche que la cat clicker
                if( categorie == this.id ){
                    var ci = cis[categorie];
                    // recup les infos de la categorie 
                    for( var label in ci ) {
                        var tmp_coord = ci[label];

                        // transformation
                        var coord = tmp_coord.split( ',' );
                        var lat =  coord[0];
                        var lng =  coord[1];

                        // marker
                        map.addMarker({
                            lat: lat,
                            lng: lng,
                            title: label,
                        });
                    }
                }
            }

            marquer_maison( coord_arrivee, label_arrivee, adr_arrivee );
        });
    });


}

function marquer_maison( coord_arrivee, label_arrivee, adr_arrivee )
{
    // la maison
    var coord = coord_arrivee.split( ',' );
    var lat =  coord[0];
    var lng =  coord[1];

    map.addMarker({
        lat: lat,
        lng: lng,
        animation: google.maps.Animation.DROP,
        title: label_arrivee,
        infoWindow: {
            content: '<h3>'+label_arrivee+'</h3><p>'+adr_arrivee+'</p>'
        }
    });
}

function init_path_region()
{
    var path = [ [40.32142,23.01213], 
    [40.257521,23.194671 ],
    [40.230267,23.305328 ],
    [40.181496,23.324554 ],
    [40.098559,23.305328],
    [40.079648,23.311508],
    [40.056001,23.341033 ],
    [40.002898,23.381546],
    [39.954491,23.355453],
    [39.96028,23.415878],
    [39.951859,23.509948],
    [39.904469,23.627365],
    [39.903416,23.713249],
    [39.91553,23.759598],
    [39.929221,23.762634],
    [39.936724,23.695595],
    [39.984092,23.683063],
    [40.00395,23.567626],
    [40.050745,23.473556],
    [40.110638,23.430984],
    [40.14424,23.395278],
    [40.182021,23.349273],
    [40.218208,23.334854],
    [40.273239,23.385665 ],
    [40.260141,23.477676],
    [40.231315,23.542221],
    [40.198806,23.67749],
    [40.060731,23.78598],
    [40.00132,23.823745],
    [39.929748,23.930862],
    [39.949227,24.00296],
    [40.030769,24.031799 ], 
    [40.108013,24.001586 ], 
    [40.162608,23.922622], 
    [40.240226,23.78186], 
    [40.283192,23.707702 ], 
    [40.343404,23.847778 ], 
    [40.30676,23.903396], 
    [40.276906,24.110763], 
    [40.10171,24.290664], 
    [40.14214,24.430053], 
    /* fin athos */
    [40.323514,24.268584 ], 
    [40.37689,24.157348 ], 
    [40.464711,24.006286 ], 
    [40.552418,23.926635 ], 
    [40.605091,23.808639 ], 
    [40.636362,23.770874], 
    [40.641573,23.662384], 
    [40.605091,23.632858], 
    [40.603527,23.545654], 
    [40.571719,23.498962], 
    [40.588407,23.407638], 
    [40.536242,23.312194], 
    [40.486649,23.299148 ], 
    [40.480904,23.159072], 
    [40.370089,23.11856 ], 
    [40.351777,23.00801 ], 
    [40.32142,23.01213]
  ];

    return path;
}
