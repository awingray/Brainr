
import { GOOGLE_API_KEY } from '../helpers/exports'
const axios = require('axios');

const mapUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=';

export const mapMixin = {
    methods: {
        getLatLng(address) {
            return axios.get(`${mapUrl}${encodeURIComponent(address)}&key=${GOOGLE_API_KEY}`);
        },
        getGeolocation(){
            return axios.post(`https://www.googleapis.com/geolocation/v1/geolocate?key=${GOOGLE_API_KEY}`);
        },
        updateMarkerCoordinates(location){
            this.coordinates = {
                lat: location.latLng.lat(),
                lng: location.latLng.lng(),
            };
            console.log(`this: ${this.lat} <--> ${this.lng}`)
            console.log(`${this.coordinates.lat} <--> ${this.coordinates.lng}`)
        }
    }
}
