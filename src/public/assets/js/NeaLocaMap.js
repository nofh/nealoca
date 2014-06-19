function NeaLocaMap()
{
    // lieux geographique
    this.maison  = new google.maps.LatLng( 40.384066,23.921043 );
    this.village = new google.maps.LatLng( 40.380895,23.923801 );
    this.region  = new google.maps.LatLng( 40.327702,23.595457 );
    this.thessa  = new google.maps.LatLng( 40.643917,22.945579 );
    this.bxl     = new google.maps.LatLng( 50.852125,4.350876 );
    this.neamoudania = new google.maps.LatLng( 40.241569,23.286589 );
    this.serres = new google.maps.LatLng( 41.092807,23.541542 );

    // villes a proximites 
    this.villes = new Array(
            { localisation :  new google.maps.LatLng( 40.397696,23.87661  ), titre: 'Ierisso' },
            { localisation :  new google.maps.LatLng( 40.326066,23.981393 ), titre : 'Ouranopolis' },
            { localisation :  new google.maps.LatLng( 40.36431,23.924562  ), titre : 'Tripiti' },
            { localisation :  new google.maps.LatLng( 40.364351,23.885767 ), titre : 'Xiropotamo' },
            { localisation :  new google.maps.LatLng( 40.365986,23.829214 ), titre : 'Develiki' },
            { localisation :  new google.maps.LatLng( 40.351344,23.799057 ), titre : 'Pirgos Chiliadous' },
            { localisation :  new google.maps.LatLng( 40.332102,23.921589 ), titre : 'Ammouliani' },
            { localisation :  this.village, titre : 'Nea Roda' }
            );
    // marqeurs
    this.maisonMarker = { titre : 'Home', texte : 'Adresse maison'};

    // conf
    this.mapOptions = { zoom: 15, center: this.maison }
    this.directionsDisplay;
    this.polygone;
    this.markers = new Array();
}

/* INTERFACE USER */
NeaLocaMap.prototype.centrerRegion = function()
{
    this.nettoyage();
    this.centrer( 8, this.region );
}

NeaLocaMap.prototype.centrerVillage = function()
{
    this.nettoyage();
    this.centrer( 15, this.village );
    this.marqueur( { titre : 'Home', texte : 'Adresse maison'} );
}

NeaLocaMap.prototype.centrerVilles = function()
{
    this.nettoyage();
    this.centrer( 11, this.village );
    this.marqueurs( this.villes );
}

NeaLocaMap.prototype.distanceEtCalcule = function(lieuDepart)
{
    this.nettoyage();
    switch( lieuDepart )
    {
        case 'thessa':
            this.distance( this.thessa );
            this.calculeDistance( this.thessa );
            break;
        case 'neamoudania':
            this.distance( this.neamoudania);
            this.calculeDistance( this.neamoudania);
            break;
        case 'serres':
            this.distance( this.serres);
            this.calculeDistance( this.serres );
            break;
        case 'bxl':
            this.distance( this.bxl );
            this.calculeDistance( this.bxl );
            break;
        default:
            this.distance( this.thessa);
            this.calculeDistance( this.thessa );
    }
}

/* PRIVATE */
// centrer la map 
NeaLocaMap.prototype.centrer = function(zoom, centre)
{
   this.mapOptions.zoom   = ( zoom === undefined ) ? 15 : zoom;
   this.mapOptions.center = ( centre === undefined ) ? this.village : centre;
   map = new google.maps.Map(document.getElementById('map-canvas'),this.mapOptions);
}

// creation de marqueurs // FIXME nom de methodes pas pratique
NeaLocaMap.prototype.marqueurs = function(mqs)
{
    for( var i = 0; i < mqs.length; i++ ) // la function du foreach empeche l'acces a la methode marqueurs
    {
        var mq = mqs[i];
        this.marqueur( mq );
    }
}

NeaLocaMap.prototype.marqueur = function(args)
{
    var localisation = ( args.localisation === undefined ) ? this.maison : args.localisation; 
    var titre = ( args.titre === undefined ) ? 'Home' : args.titre; 
    var anime = ( args.anime === undefined ) ? '' : google.maps.Animation.DROP;
    var image = ( args.image === undefined ) ? '' : args.image;
    //console.log('loca ' + localisation + ' titre ' + titre + ' anime ' + anime  );

  var marker = new google.maps.Marker({
      position: localisation,
      map: map,
      title: titre,
      animation: anime,
      icon: image
  });

  // infosBox
  if( args.texte !== undefined )
  {
      var infoWindow = new google.maps.InfoWindow({ content: args.texte });
      google.maps.event.addListener( marker, 'click', function()
              {
                  infoWindow.open( map, marker );
              });
  }

  this.markers.push( marker );
}

// calcule de distance
NeaLocaMap.prototype.distance = function(depart, arrivee)
{
    var depart  = ( depart === undefined ) ? this.thessa: depart; 
    var arrivee = ( arrivee === undefined ) ? this.maison : arrivee;

    var request = { 
        origin: depart, 
        destination: arrivee, 
        travelMode: google.maps.TravelMode.DRIVING 
        };

    directionsDisplay = new google.maps.DirectionsRenderer();    
    var directionsService = new google.maps.DirectionsService();
    directionsService.route( request, function( reponse, statut )
            {
                if( statut == google.maps.DirectionsStatus.OK )
                {
                    directionsDisplay.setDirections( reponse );
                }
            });

    directionsDisplay.setMap( map );
    this.directionsDisplay = directionsDisplay;
}

NeaLocaMap.prototype.calculeDistance = function( depart, arrivee)
{
    var depart  = ( depart === undefined ) ? this.thessa: depart; 
    var arrivee = ( arrivee === undefined ) ? this.maison : arrivee;
    
    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix(
    {
      origins: [depart],
      destinations: [arrivee],
      travelMode: google.maps.TravelMode.DRIVING,
      unitSystem: google.maps.UnitSystem.METRIC,
      avoidHighways: false,
      avoidTolls: false
    }, function(response, status)
    {
       if (status != google.maps.DistanceMatrixStatus.OK) 
  {
    alert('Error was: ' + status);
  } 
  else 
  {
    // preparation de l'info recuperer pour l'affichage
    var origins = response.originAddresses;
    var destinations = response.destinationAddresses;
    for (var i = 0; i < origins.length; i++) 
    {
      var results = response.rows[i].elements;
      for (var j = 0; j < results.length; j++)
      {
    
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

// region et polygone
NeaLocaMap.prototype.chalkidiki = function()
{
    this.polygone = new google.maps.Polygon({
    paths: this.getCoordonneesChalkidiki(),
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35
  });

  this.polygone.setMap(map);
}

NeaLocaMap.prototype.getCoordonneesChalkidiki = function()
{   
    return [
    new google.maps.LatLng(  40.32142,23.01213), 
    new google.maps.LatLng( 40.257521,23.194671 ),
    new google.maps.LatLng( 40.230267,23.305328 ),
    new google.maps.LatLng( 40.181496,23.324554 ),
    new google.maps.LatLng(  40.098559,23.305328),
    new google.maps.LatLng(  40.079648,23.311508),
    new google.maps.LatLng( 40.056001,23.341033 ),
    new google.maps.LatLng(  40.002898,23.381546),
    new google.maps.LatLng(  39.954491,23.355453),
    new google.maps.LatLng(  39.96028,23.415878),
    new google.maps.LatLng(  39.951859,23.509948),
    new google.maps.LatLng(  39.904469,23.627365),
    new google.maps.LatLng( 39.903416,23.713249),
    new google.maps.LatLng(  39.91553,23.759598),
    new google.maps.LatLng(  39.929221,23.762634),
    new google.maps.LatLng(39.936724,23.695595),
    new google.maps.LatLng( 39.984092,23.683063),
    new google.maps.LatLng(  40.00395,23.567626),
    new google.maps.LatLng(  40.050745,23.473556),
    new google.maps.LatLng(  40.110638,23.430984),
    new google.maps.LatLng(  40.14424,23.395278),
    new google.maps.LatLng(  40.182021,23.349273),
    new google.maps.LatLng( 40.218208,23.334854),
    new google.maps.LatLng( 40.273239,23.385665 ),
    new google.maps.LatLng(  40.260141,23.477676),
    new google.maps.LatLng(  40.231315,23.542221),
    new google.maps.LatLng(  40.198806,23.67749),
    new google.maps.LatLng(  40.060731,23.78598),
    new google.maps.LatLng(  40.00132,23.823745),
    new google.maps.LatLng(  39.929748,23.930862),
    new google.maps.LatLng(  39.949227,24.00296),
    new google.maps.LatLng( 40.030769,24.031799 ), 
    new google.maps.LatLng( 40.108013,24.001586 ), 
    new google.maps.LatLng(  40.162608,23.922622), 
    new google.maps.LatLng(  40.240226,23.78186), 
    new google.maps.LatLng( 40.283192,23.707702 ), 
    new google.maps.LatLng( 40.343404,23.847778 ), 
    new google.maps.LatLng(  40.30676,23.903396), 
    new google.maps.LatLng(  40.276906,24.110763), 
    new google.maps.LatLng(  40.10171,24.290664), 
    new google.maps.LatLng(  40.14214,24.430053), 
    /* fin athos */
    new google.maps.LatLng( 40.323514,24.268584 ), 
    new google.maps.LatLng( 40.37689,24.157348 ), 
    new google.maps.LatLng( 40.464711,24.006286 ), 
    new google.maps.LatLng( 40.552418,23.926635 ), 
    new google.maps.LatLng( 40.605091,23.808639 ), 
    new google.maps.LatLng(  40.636362,23.770874), 
    new google.maps.LatLng(  40.641573,23.662384), 
    new google.maps.LatLng(  40.605091,23.632858), 
    new google.maps.LatLng(  40.603527,23.545654), 
    new google.maps.LatLng(  40.571719,23.498962), 
    new google.maps.LatLng(  40.588407,23.407638), 
    new google.maps.LatLng(  40.536242,23.312194), 
    new google.maps.LatLng( 40.486649,23.299148 ), 
    new google.maps.LatLng(  40.480904,23.159072), 
    new google.maps.LatLng( 40.370089,23.11856 ), 
    new google.maps.LatLng( 40.351777,23.00801 ), 
    new google.maps.LatLng(  40.32142,23.01213)
  ];
}
// nettoayge de la map, supp les makrers, itineraire, polygone
NeaLocaMap.prototype.nettoyage = function()
{
    if( this.directionsDisplay ) 
    {
        this.directionsDisplay.setMap( null );
    }

    if( this.polygone ) 
    {
        this.polygone.setMap( null );
    }
    
    if( this.markers )
    {
        this.markers.forEach( function( marker )
                {
                    marker.setMap(null);
                });
        this.markers = new Array();
    }
}
