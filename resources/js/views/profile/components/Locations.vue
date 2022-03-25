<template>
    <div class="rounded border-2 px-3 py-2 mb-3">
        <h2 class="text-2xl font-bold mb-3">Locations</h2>
        
        <b-api :url="profileLocationsUrl" v-slot:default="{data:locations}" ref="locations">
            <div class="mb-3">

                <div v-for="location in locations" :key="location.id">
                    <p class="break-words">
                        <span>
                            {{ location.country }}
                        </span>
                         -
                        {{ location.address }}
                        <a class="text-red-700"
                           href="#"
                           @click.prevent="deleteLocation(location)"
                        >Delete</a>
                    </p>
                </div>

                <p v-if="!locations.length">No locations</p>
            </div>
        </b-api>

        <!-- Create location form -->
        <button class="w-full inline-block text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded focus:outline-none focus:shadow-outline"
                @click="$refs.create.open()"
        >Create location
        </button>
        <Modal ref="create" title="Create New Location">
            <b-form :action="profileLocationsUrl"
                    method="post"
                    @submitted="onCreate"
                    v-slot:default="{data, errors}"
            >

                <div class="w-full">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="country"
                    >
                        Country
                    </label>
                    <select class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="country"
                            v-model="data.country"
                    >
                        <option v-for="(name, code) in countries" :value="name" v-text="name"></option>
                    </select>

                    <p class="text-red-500 text-xs italic" v-if="errors.has('country')"
                       v-text="errors.get('country')"
                    ></p>
                </div>

                <div class="w-full">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                           for="address"
                    >
                        Address
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                           id="address"
                           type="text"
                           placeholder="address"
                           v-model="data.address"
                    >

                    <p class="text-red-500 text-xs italic" v-if="errors.has('address')"
                       v-text="errors.get('address')"
                    ></p>
                </div>
                <Map :lat='lat' :lng='lng' :usermarkers="markers" />
                <div class="w-full">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit"
                    >
                        Create
                    </button>
                    <a href="#"
                       class="bg-gray-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block focus:outline-none focus:shadow-outline"
                       @click.prevent="$refs.create.close()"
                    >
                        Cancel
                    </a>
                </div>
            </b-form>
        </Modal>
        <!-- End create Location form -->
    </div>
</template>

<script>
    import Modal from '../../../components/Modal';
    import brainr from '../../../apis/brainr';
    import { COUNTRIES } from "../helpers/exports";
    import { mapMixin } from "../mixins/mapMixin";
    import Map from "./Map";

    export default {
        name: "Locations",
        data()
        {
            return {
                countries: COUNTRIES.map(country => country.name),
                markers: null,
                lat: 0,
                lng: 0,
                showMapDialog: false,
                addresses: null,
            };
        },
        mixins: [mapMixin],
        components: {
            Modal,
            Map,
        },
        props: {
            profile: {
                required: true,
            },
        },
        mounted () {
            this.getMarkers();
            this.geolocation(); 
            this.getAddress();          
        },
        computed: {
            profileLocationsUrl()
            {
                return `/api/profiles/${ this.profile }/locations`;
            },
            markersUrl()
            {
                return `/api/profiles/${ this.profile }/locations/get-marker`;
            },
            addressUrl()
            {
                return `/api/profiles/${ this.profile }/locations/get-address`;
            },
        },
        methods: {
            onCreate()
            {
                this.$refs.locations.loadData();
                this.getMarkers();
                this.$refs.create.close();
            },
            onUpdate()
            {
                this.$refs.locations.loadData();
                // this.$refs.update.close();
            },
            deleteLocation(location)
            {
                if (confirm('Are you sure?'))
                {
                    brainr.delete(`${ this.profileLocationsUrl }/${ location.id }`).
                        then(() =>
                        {
                            this.$refs.locations.loadData();
                            this.getMarkers();
                        }).
                        catch(() =>
                        {
                            alert('Failed to delete location');
                        });
                }
            },
            async openMap(location) {
                try {
                    const response = await this.getLatLng(`${location}`);
                    const loc = response.data.results[0].geometry.location;
                    this.lat = loc.lat;
                    this.lng = loc.lng;
                    this.showMapDialog = true;
                } catch (ex) {
                    console.log(ex);
                }
            },
            async geolocation () {
                try {
                    const response = await this.getGeolocation();
                    const location = response.data.location;
                    this.lat = location.lat;
                    this.lng = location.lng;
                } catch (ex) {
                    console.log(ex);
                }
            },
            async getAddress() {
                try {
                    await brainr
                        .get(`${ this.addressUrl }`, {params: { lat: 6.550408470382355, lng: 3.339059199999994}})
                        .then(response => this.addresses = JSON.parse(JSON.stringify(response.data)))
                        .catch(() => {alert('Unable to load markers');});
                } catch (ex) {
                    console.log(ex);
                }
            },
            async getMarkers() {
                try {
                    await brainr
                        .get(`${ this.markersUrl }`)
                        .then(response => this.markers = JSON.parse(JSON.stringify(response.data)))
                        .catch(() => {alert('Unable to load markers');});
                } catch (ex) {
                    console.log(ex);
                }
            }
        },
    };
</script>

<style scoped>
    .map {
        height: 400px;
    }

    @media (min-width: 768px){
        .mp {
            max-width: 58rem !important;
        }
    }
</style>
