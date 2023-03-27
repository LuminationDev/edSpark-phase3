<script >
    import { ref, computed } from 'vue';
    import axios from 'axios';
    import { GoogleMap, Marker, MarkerCluster } from 'vue3-google-map'

    /**
     * Import Components
     */
    import SchoolsMapPopup from './SchoolsMapPopup.vue';
    import SchoolsMapFilterBar from './SchoolsMapFilterBar.vue';

    export default {

        setup() {
            const showFilters = ref(false);
            const toggleFilters = computed(() => {
                console.log('toggle', showFilters.value);
                showFilters.value = !showFilters.value;
            });

            const toggleMapPopup =ref(false);

            const center = { lat: 40.689247, lng: -74.044502 };

            return {
                toggleFilters,
                showFilters,
                center,
                toggleMapPopup,
            }
        },

        components: {
            GoogleMap,
            Marker,
            MarkerCluster,
            SchoolsMapPopup,
            SchoolsMapFilterBar
        },

        data() {
            return {
                center: { lat: -34.9285, lng: 138.6007 },
                zoom: 12,
                options: {
                    zoomControl: false,
                    mapTypeControl: false,
                    scaleControl: false,
                    streetViewControl: false,
                    rotateControl: false,
                    fullscreenControl: false,
                    mapId: '164f2a0469c00794'
                },
                mapPopupName: null,
                popupX: '',
                popupY: '',
                elWidth: null,
                elHeight: null,
                schools: [
                    {
                        name: 'Adelaide Botanic high School',
                        metadata: {
                            location: {
                                lat: -34.9172, lng: 138.6067
                            }
                        }
                    },
                    {
                        name: 'Adelaide high School',
                        metadata: {
                            location: {
                                lat: -34.9256, lng: 138.5870
                            }
                        }
                    },
                    {
                        name: 'East Adelaide School',
                        metadata: {
                            location: {
                                lat: -34.9054, lng: 138.6268
                            }
                        }
                    },
                    {
                        name: 'North Adelaide Primary School',
                        metadata: {
                            location: {
                                lat: -34.90806, lng: 138.59458
                            }
                        }
                    },
                ],
                mapInput: ''
            }
        },

        computed: {
            filteredList() {
                return this.schools.filter(school => {
                    // Use toLowerCase() to make the search case-insensitive
                    return school.name.toLowerCase().includes(this.mapInput.toLowerCase());
                });
            }
        },

        methods: {
            handleOnClusterClick(location, index) {

                if (this.toggleMapPopup) {
                    this.toggleMapPopup = false;
                }

                this.toggleMapPopup = !this.toggleMapPopup;
                this.mapPopupName = location.name;

                console.log(this.elWidth);

                this.popupX = event.clientX / 2;
                this.popupY = event.clientY / 2;
            },

            handleTogglePopupEmit() {
                console.log('emitted event')
                this.toggleMapPopup = !this.toggleMapPopup
            },

            handleFilterBarClick() {
                this.showFilters = !this.showFilters
            }
        },


        mounted() {
            console.log('show filters', this.schoolList);

        }
    }
</script>

<template>
    <div class="w-full relative border border-[#0072DA]" ref="gMapParent">
        <div class="bg-[#0072DA] h-[72px] flex flex-row place-items-center justify-between px-[48px] relative z-50">
            <h1 class="text-[30px] font-bold text-white">Search for Schools</h1>

            <button class="bg-white text-[#0072DA] px-8 py-3 w-[120px]" @click="handleFilterBarClick">
                {{ (showFilters ? 'Close' : 'Filters') }}
            </button>
        </div>

        <SchoolsMapFilterBar
            :showFilters="this.showFilters"
            v-model="mapInput"
        />

        <div class="relative">
            <div ref="map">
                <GoogleMap
                    api-key="AIzaSyAFbqxGQntzgzfzKFh6bArwU14MJhcV1Wc"
                    style="width: 100%; height: 700px"
                    :center="center"
                    :zoom="this.zoom"
                    :options="this.options"

                >
                    <MarkerCluster>
                        <!-- <Marker class="relative" v-for="(location, i) in (this.mapInput === '' ? this.schools: this.filteredList)" :options="{ position: location.metadata.location }" :key="i" @click="handleOnClusterClick(location, i)"> -->
                        <Marker
                            class="relative"
                            v-for="(location, i) in this.filteredList"
                            :options="{ position: location.metadata.location }"
                            :key="i"
                            @click="handleOnClusterClick(location, i)"

                        />
                    </MarkerCluster>
                    <SchoolsMapPopup
                        v-if="toggleMapPopup"
                        :class="toggleMapPopup ? `top-[${this.popupY}px] left-[${this.popupX}px]`: ''"
                        :mapPopupName="this.mapPopupName"
                        @handleToggle="handleTogglePopupEmit"
                    />
                </GoogleMap>
            </div>
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
