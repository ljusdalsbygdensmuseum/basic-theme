import * as L from "leaflet"
import '/node_modules/leaflet/dist/leaflet.css';
import { Draggable } from 'leaflet';

import {leafletControlGeocoder} from 'leaflet-control-geocoder'
import '/node_modules/leaflet-control-geocoder/dist/Control.Geocoder.css';

const $ = jQuery;

class leafletMaps{
    constructor(container, center, adress = '', name = '', zoom = 13, markers = null, searchable = false){
        // Return if the container does not exist
        if($('#'+container).length < 1){
            return;
        }

        // Public variables
        this.center = center;
        this.markers = markers;
        this.searchable = searchable;
        this.adress = adress;
        this.name = name;

        this.typingTimeout;

        // Replaces values if there are saved data
        
        if($('#leaflet-post-value').length >= 1){
            const values = $('#leaflet-post-value').val().split(':;:');
            if (values[1].length >= 1) {
                this.adress = values[1];
            }
            if (values[2].length >=1 && values[3].length >= 1) {
                this.center = {lat: values[2], lng: values[3]};
            }
            if (values[0].length >= 1) {
                this.name = values[0];
            }
        }
        

        // For search
        this.provider = new L.Control.Geocoder.ArcGis();// ---------------------------------------------might need an api key
        
        // Constructing map
        this.map = L.map(container).setView([this.center.lat, this.center.lng], zoom);

        // Geting and loading tiles
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(this.map);

        // Reloads the tiles in case of bug where not all tiles are shown
        setTimeout( () =>{this.reload()}, 500);

        // Adds markers
        this.marker();

        // If searchable AKA backend
        if (this.searchable) {
            this.form();
            this.event();
            
        }

    }
    reload(){
        this.map.invalidateSize();
       
    }
    marker(){
        // Making a custom marker ----------------I have to fix the marker so that it isnt janky
        const markerIcon = new L.Icon({
            iconUrl: universalData.root_url+'/wp-content/themes/basic-theme/src/js/modules/images/leafletmarker.png',
            iconSize: [30, 40],
            iconAnchor: [15, 39]
        });

        if(this.searchable){
            // Searchable AKA backend marker
            // Adding marker
            this.markers = L.marker(this.center, {icon: markerIcon, draggable: true, autoPan: true}).addTo(this.map);

            // function for draging
            this.markers.on('dragend', (data)=>{

                this.reverse(data);
            });
            
        }else{
            // Frontend markers
            if(this.markers.length > 0){
                this.markers.forEach(marker => {
                    // Adding marker at location
                    const printMarker = L.marker(marker.latLng, {icon: markerIcon}).addTo(this.map);

                    // Popup
                    if(marker.popup){
                        printMarker.bindPopup('<a href="'+marker.data[7]+'">'+marker.data[6]+'</a><p>'+marker.data[1]+'</p>');
                    }
                }); 
            }
        }
        
    }
    move_to(){
        // Move map and marker to searched position
        this.map.flyTo(this.center);
        this.markers.setLatLng(this.center);
    }
    search(data){
        this.provider.geocode(data.target.value, (result)=>{
            this.center = result[0].center;
            this.adress = result[0].name;
            this.populate_form();
            this.move_to();
        });
    }
    reverse(data){
        this.provider.reverse(data.target._latlng, 13, (result)=>{
            this.center = result[0].center;
            this.adress = result[0].name;
            this.populate_form();
        });
    }
    suggest(data){
        this.provider.suggest(data.target.value, (data_list)=>{
            this.populate_suggested(data_list);
        });
    }
    print_latLng(){
        
        // Lat and lng
        let lat = this.center.lat;
        let lng = this.center.lng;
    
        const latLng = 
        `<ul id="leaflet-latlng-display">
            <li data-lat="`+lat+`">Lat: `+this.center.lat+`</li>
            <li data-lng="`+lng+`">Long: `+lng+`</li>
        </ul>`;

        return latLng
    }
    form(){
        // Grabs values
        const values = $('#leaflet-post-value').val().split(':;:');

        // Form parts
        const adressbox = 
        `<div>
            <div id="leaflet-adress-container">
                <label for="leaflet-adress">Adress</label>
                </br>
                <input type="text" id="leaflet-adress" name="leaflet-adress" value="`+this.adress+`">
                <ul id="leaflet-search-results"></ul>
            </div>
            <input type="checkbox" id="leaflet-custom-adress" name="leaflet-custom-adress">
            <label for="leaflet-custom-adress">Adress separat från markören på kartan</label>
            </br>
        </div>`;

        const namebox = 
        `<div>
            <div id="leaflet-descriptive-name-container" style="display: none;">
                <label for="leaflet-descriptive-name">Beskrivande namn:</label>
                </br>
                <input type="text" id="leaflet-descriptive-name" name="leaflet-descriptive-name" value="`+this.name+`">
                <p class="metabox-description">ex. Fenix, Röda kvarn, Biblioteket</p>
            </div>
            <input type="checkbox" id="leaflet-custom-descriptive-name" name="leaflet-custom-descriptive-name">
            <label for="leaflet-custom-descriptive-name">Eget beskrivande namn</label>
            </br>
        </div>`;

        const latLngbox = this.print_latLng();

        // Prints form
        $('#map_information').empty();
        $('#map_information').append(adressbox, namebox, latLngbox);

        // Check checkboxes
        if(values[4] == 'true'){
            $('#leaflet-custom-adress').prop('checked', true);
        }
        if(values[5] == 'true'){
            $('#leaflet-custom-descriptive-name').prop('checked', true);
            $('#leaflet-descriptive-name-container').css('display', 'block');
        }
    }
    populate_form(){
        // Prints adress if custom adress is not checked
        if(!$('#leaflet-custom-adress').is(':checked')){
            $('#leaflet-adress').val(this.adress);
        }
        // Prints latLng
        $('#leaflet-latlng-display').empty();
        $('#leaflet-latlng-display').append(this.print_latLng());

        this.populate_save_field();
    }
    populate_save_field(){
        // Grabs values from form
        const adress = $('#leaflet-adress').val();
        const name = $('#leaflet-descriptive-name').val();
        const lat = $('#leaflet-latlng-display li')[0].dataset.lat;
        const lng = $('#leaflet-latlng-display li')[1].dataset.lng;
        const adress_check = $('#leaflet-custom-adress').is(':checked');
        const name_check = $('#leaflet-custom-descriptive-name').is(':checked');

        const values = name+':;:'+adress+':;:'+lat+':;:'+lng+':;:'+adress_check+':;:'+name_check;

        // Prints values to field that is saved through php 
        $('#leaflet-post-value').val(values);

    }
    populate_suggested(data){
        // Removes old suggests
        $('#leaflet-search-results').empty();

        data.forEach(function(result){
            const name = result.name;
            const lat = result.center.lat;
            const lng = result.center.lng;
            // Check if this adress is to close to an existing adress 
            let repeted = false;
            $('.leaflet-search-result-item').each(function(i, object){
                if (Math.round(lat) == Math.round(object.dataset.lat) && Math.round(lng) == Math.round(object.dataset.lng)) {
                    repeted = true;
                }
            });
            if (!repeted) {
                // Prints search results
                $('#leaflet-search-results').append('<li class="leaflet-search-result-item" data-lat="'+lat+'" data-lng="'+lng+'">'+name+'</li>');
            }
        });
   
    }
    event(){
        // Writing in adress field
        $('#leaflet-adress').on('keyup', (event)=>{
            if(!$('#leaflet-custom-adress').is(':checked')){
                if(event.key == 'Enter' || event.key == 'enter' || event.key == 13){
                    this.search(event);
                }else{
                    clearTimeout(this.typingTimeout);
                    this.typingTimeout = setTimeout(()=>this.suggest(event), 800);
                }
            }
        });

        // Clicking search results
        $('#leaflet-search-results').on('click', '.leaflet-search-result-item', (event)=> {
            // Set global center
            this.center.lat = event.target.dataset.lat;
            this.center.lng = event.target.dataset.lng;

            // Set global adress
            this.adress = event.target.innerHTML;

            // Move map and marker to searched position            
            this.move_to();

            // Populate form
            this.populate_form();
    
        });

        // Writing in name field
        $('#leaflet-descriptive-name').on('keyup', (event)=>{
            this.populate_save_field();
        })

        // Checking checkboxes
        $('#leaflet-custom-descriptive-name').on('click', (event)=>{
            this.populate_save_field();
            // Hide section when not selected
            if ($('#leaflet-custom-descriptive-name').is(':checked')) {
                $('#leaflet-descriptive-name-container').css('display', 'block');
            }else{
                $('#leaflet-descriptive-name-container').css('display', 'none');
            }
        });

        $('#leaflet-custom-adress').on('click', (event)=>{
            this.populate_save_field();
        });
    }
}


export default leafletMaps