const $ = jQuery;

import leafletMaps from './modules/leaflet-maps';
import liveSearch from './modules/live-search';

if($('#map_container').length > 0){
    let markers = [];
    let bounds = [{lat: 0, lng: 0}, {lat: 10000, lng: 10000}];
    $('.leaflet-marker').each(i => {
        let values = $('.leaflet-marker')[i].dataset.info.split(':;:');

        // Pushing the current marker into array of all markers
        markers.push({latLng: {lat: values[2], lng: values[3]}, data: values, popup: true});

        // Takes most distant points and sets the bounds acordingly
        if(values[2] > bounds[0].lat){
            bounds[0].lat = values[2];
        }
        if(values[3] > bounds[0].lng){
            bounds[0].lng = values[3];
        }
        if(values[2] < bounds[1].lat){
            bounds[1].lat = values[2];
        }
        if(values[3] < bounds[1].lng){
            bounds[1].lng = values[3];
        }
    });
    
    // Prints map
    const theMap = new leafletMaps('map_container', {lat: 61.827348, lng: 16.089069}, '', '', 13, markers, false);

    // Change bounds
    theMap.map.fitBounds(bounds);
}

const search = new liveSearch();