<script >
    import { ref, computed } from 'vue';
    import axios from 'axios';
    import { GoogleMap, Marker } from 'vue3-google-map'

    export default {

        setup() {
            const showFilters = ref(false);
            const toggleFilters = computed(() => {
                console.log('toggle');
                showFilters.value = !showFilters.value;
            });



            const locations = [
                { lat: -34.9256, lng: 138.5870 },
                { lat: -34.92577, lng: 138.58661 },
                { lat: -34.904451, lng: 138.597501 },
                { lat: -35.033562, lng: 138.626758 },
                // { lat: -33.851702, lng: 151.216968 },
                // { lat: -34.671264, lng: 150.863657 },
                // { lat: -35.304724, lng: 148.662905 },
                // { lat: -36.817685, lng: 175.699196 },
                // { lat: -36.828611, lng: 175.790222 },
                // { lat: -37.75, lng: 145.116667 },
                // { lat: -37.759859, lng: 145.128708 },
                // { lat: -37.765015, lng: 145.133858 },
                // { lat: -37.770104, lng: 145.143299 },
                // { lat: -37.7737, lng: 145.145187 },
                // { lat: -37.774785, lng: 145.137978 },
                // { lat: -37.819616, lng: 144.968119 },
                // { lat: -38.330766, lng: 144.695692 },
                // { lat: -39.927193, lng: 175.053218 },
                // { lat: -41.330162, lng: 174.865694 },
                // { lat: -42.734358, lng: 147.439506 },
                // { lat: -42.734358, lng: 147.501315 },
                // { lat: -42.735258, lng: 147.438 },
                // { lat: -43.999792, lng: 170.463352 },
            ]

            const center = { lat: 40.689247, lng: -74.044502 }

            return {
                toggleFilters,
                showFilters,
                center,
                locations
            }
        },

        watch: {
            showFilters: {
                handler(newVal, oldVal) {

                }
            }
        },

        components: {
            GoogleMap,
            Marker
        },

        data() {
            return {
                center: { lat: -34.9285, lng: 138.6007 },
                zoom: 12,
                savedLocations: [],
                // markers: [
                //     {
                //         position: {
                //             lat: 51.093048, lng: 6.842120
                //         },
                //     }
                // ],
                options: {
                    zoomControl: true,
                    mapTypeControl: true,
                    scaleControl: true,
                    streetViewControl: true,
                    rotateControl: true,
                    fullscreenControl: true,
                    mapId: '164f2a0469c00794'
                }
            }
        },

        methods: {
            handleOnClusterClick(location, index) {
                console.log('Event clicked', location, index);
            }
        },


        mounted() {
            console.log(this.markers);


        }
    }
</script>

<template>
    <div class="w-full relative border border-[#0072DA]">
        <div class="bg-[#0072DA] h-[72px] flex flex-row place-items-center justify-between px-[48px] relative z-50">
            <h1 class="text-[30px] font-bold text-white">Search for Schools</h1>

            <button class="bg-white text-[#0072DA] px-8 py-3" @click="this.showFilters = !this.showFilters">
                {{ (showFilters ? 'Close' : 'Filters') }}
            </button>
        </div>

        <div class="absolute transition-all top-0 bottom-0 w-full bg-[#0072DA] z-40" :class="showFilters ? 'h-[350px]' : '!h-0' ">

        </div>
        <div class="relative">
            <div ref="map">
                <GoogleMap
                    api-key="AIzaSyAFbqxGQntzgzfzKFh6bArwU14MJhcV1Wc"
                    style="width: 100%; height: 700px"
                    :center="center"
                    :zoom="15"
                    :options="this.options"
                >
                    <!-- <Marker v-for="(m, index) in this.markers" :options="markerOptions" /> -->
                    <MarkerCluster>
                        <Marker v-for="(location, i) in locations" :options="{ position: location }" :key="i" @click="handleOnClusterClick(location, i)" />
                    </MarkerCluster>
                </GoogleMap>
            </div>

            <!-- <GMapMap
                :center="this.center"
                :options="'164f2a0469c00794'"
                :zoom="this.zoom"
                :disableDefaultUI="true"
                map-type-id="terrain"
                style="width: 100%; height: 700px">
                    <GmapMarker
                        :key="index"
                        v-for="(m, index) in this.markers"
                        :position="m.position"
                        :clickable="true"
                        :draggable="false"
                        :icon= '{
                            url: "https://image.flaticon.com/teams/slug/google.jpg",
                            scaledSize: {width: 77, height: 77},
                            labelOrigin: {x: 16, y: -10}
                        }'
                    />
            </GMapMap> -->
        </div>

    </div>
</template>

<style>
    /* .vue-map {
        max-width: 100% !important;
    } */

    /* .vue-map-container {
        z-index: -10;
        position: relative;
    } */

</style>
