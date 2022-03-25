<template>
<!-- @click="l" -->
  <GmapMap 
    :center="{lat, lng}" 
    :zoom="15" 
    style="width: auto; height: 400px"
    ref="mapRef"
    >
    
    <GmapMarker
        :key="index"
        v-for="(m, index) in usermarkers"
        :position="m.position"
        :clickable="true"
        :draggable="false"
        @drag="updateCoordinates"
        @click="toggleInfoWindow(m,index)"
    />
    <GmapMarker
        :position="google && new google.maps.LatLng(lat, lng)"
        :clickable="true"
        :draggable="true"
        @drag="updateCoordinates"
        :icon="{ url: 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/88/Map_marker.svg/25px-Map_marker.svg.png'}"
        
    />
     <gmap-info-window
        :options="infoOptions"
        :position="infoWindowPos"
        :opened="infoWinOpen"
        @closeclick="infoWinOpen=false"
      >
        <div v-html="infoContent"></div>
    </gmap-info-window>
  </GmapMap>
</template>
<script>
    import { gmapApi } from "vue2-google-maps";
    export default {
        name: "Map",
        props: {
            lat: Number,
            lng: Number,
            usermarkers: Array
        },
        computed: {
            google: gmapApi
        }, 
        mounted() {
            // set bounds of the map
            this.$refs.mapRef.$mapPromise.then((map) => {
                const bounds = new google.maps.LatLngBounds()
                for (let m of this.usermarkers) {
                bounds.extend(m.position)
                }
                map.fitBounds(bounds);
            });
        },
        data() {
            return {
                coordinates: null,
                infoContent: '',
                infoWindowPos: {
                    lat: 0,
                    lng: 0
                },
                infoWinOpen: false,
                currentMidx: null,
                //optional: offset infowindow so it visually sits nicely on top of our marker
                infoOptions: {
                    pixelOffset: {
                        width: 0,
                        height: -35
                    }
                }
            }
        },
        methods: {
            updateCoordinates(location) {
                this.coordinates = {
                    lat: location.latLng.lat(),
                    lng: location.latLng.lng(),
                };
                console.log(`this: ${this.lat} <--> ${this.lng}`)
                console.log(`${this.coordinates.lat} <--> ${this.coordinates.lng}`)
            },
            toggleInfoWindow: function (marker, idx) {
                this.infoWindowPos = marker.position;
                this.infoContent = this.getInfoWindowContent(marker);
                

                //check if its the same marker that was selected if yes toggle
                if (this.currentMidx == idx) {
                    this.infoWinOpen = !this.infoWinOpen;
                }
                //if different marker set infowindow to open and reset current marker index
                else {
                    this.infoWinOpen = true;
                    this.currentMidx = idx;
                }
            },

            getInfoWindowContent: function (marker) {
                return (
                    `<div class="card">
                        <div class="card-content">
                            <div class="media">
                            <div class="media-content">
                                <p class="title is-4">${marker.country}</p>
                            </div>
                            </div>
                            <div class="content">
                            ${marker.address}
                            <br>
                            
                            </div>
                        </div>
                    </div>`
                );
            },
        }
    };
</script>
<style scoped>

</style>