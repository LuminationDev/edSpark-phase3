<script>
import { ref, computed } from 'vue';
import axios from 'axios';
import { GoogleMap, Marker, MarkerCluster, } from 'vue3-google-map'
import { useRouter } from 'vue-router';

/**
 * Import Components
 */
import SchoolsMapPopup from './SchoolsMapPopup.vue';
import SchoolsMapFilterName from './SchoolsMapFilterName.vue';
import SchoolsMapFilterType from './SchoolsMapFilterType.vue';
import SchoolsMapFilterTech from './SchoolsMapFilterTech.vue';

export default {

    components: {
        GoogleMap,
        Marker,
        MarkerCluster,
        SchoolsMapPopup,
        SchoolsMapFilterName,
        SchoolsMapFilterType,
        SchoolsMapFilterTech
    },

    setup() {
        const router = useRouter();
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
            router
        }
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
            mapPopupIndex: null,
            popupX: '',
            popupY: '',
            elWidth: null,
            elHeight: null,
            schools: [
                {
                    id: 'abhs',
                    name: 'Adelaide Botanic high School',
                    type: 'High',
                    techUsed: [
                        'VR',
                        'AR',
                        'Drones'
                    ],
                    metadata: {
                        location: {
                            lat: -34.9172, lng: 138.6067
                        }
                    }
                },
                {
                    id: 'ahs',
                    name: 'Adelaide high School',
                    type: 'High',
                    techUsed: [
                        'VR',
                        'Drones',
                        'Robotics'
                    ],
                    metadata: {
                        location: {
                            lat: -34.9256, lng: 138.5870
                        }
                    }
                },
                {
                    id: 'eas',
                    name: 'East Adelaide School',
                    type: 'Area',
                    techUsed: [
                        'Robotics',
                        'Drones'
                    ],
                    metadata: {
                        location: {
                            lat: -34.9054, lng: 138.6268
                        }
                    }
                },
                {
                    id: 'naps',
                    name: 'North Adelaide Primary School',
                    type: 'Primary',
                    techUsed: [
                        'VR',
                        'AR',
                        'IoT'
                    ],
                    metadata: {
                        location: {
                            lat: -34.90806, lng: 138.59458
                        }
                    }
                },
            ],
            schoolNameFilter: '',
            schoolTypeFilter: 'All',
            schoolTechFilter: null
        }
    },

    computed: {
        filteredList() {
            let filtered = this.schools;

            if (this.schoolNameFilter) {
                filtered = filtered.filter(school => {
                    // Use toLowerCase() to make the search case-insensitive
                    return school.name.toLowerCase().includes(this.schoolNameFilter.toLowerCase());
                });
            }

            if (this.schoolTypeFilter) {
                filtered = filtered.filter(school => {
                    if (this.schoolTypeFilter === 'All') {
                        return this.schools;
                    } else {
                        return school.type === this.schoolTypeFilter
                    }
                });
            }

            if (this.schoolTechFilter) {
                filtered = filtered.filter(school => {
                    if (this.schoolTechFilter === 'All') {
                        return this.schools;
                    } else {
                        return school.techUsed.includes(this.schoolTechFilter);
                    }
                })
            }

            return filtered;
        }
    },


    mounted() {
        console.log('show filters', this.schoolList);

    },

    methods: {
        handleOnClusterClick(location, index) {

            if (this.toggleMapPopup) {
                this.toggleMapPopup = false;
            }

            this.toggleMapPopup = !this.toggleMapPopup;
            this.mapPopupIndex = location.id;
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
        },

        handleLinkToSchool() {
            this.schools.forEach(school => {
                const idMatch = school.id;
                if (idMatch.includes(this.mapPopupIndex)) {
                    let schoolUrlFriendly = school.name.replace(/\s+/g, '-').toLowerCase();
                    this.router.push({
                        name: 'schoolSingle',
                        params: { name: school.name }
                    })
                }
            })
        }
    }
}
</script>

<template>
    <div
        ref="gMapParent"
        class="w-full relative border border-[#0072DA]"
    >
        <div class="bg-[#0072DA] h-[72px] flex flex-row place-items-center justify-between px-[48px] relative z-50">
            <h1 class="text-[30px] font-bold text-white">
                Search for Schools
            </h1>

            <button
                class="bg-white text-[#0072DA] px-8 py-3 w-[120px]"
                @click="handleFilterBarClick"
            >
                {{ (showFilters ? 'Close' : 'Filters') }}
            </button>
        </div>

        <div
            class="absolute p-6 transition-all top-0 bottom-0 w-full bg-[#0072DA] z-40"
            :class="showFilters ? 'h-[350px]' : '!h-0 opacity-0' "
        >
            <div class="mt-12 flex flex-row flex-wrap gap-6">
                <SchoolsMapFilterName
                    v-model="schoolNameFilter"
                />

                <SchoolsMapFilterType
                    v-model="schoolTypeFilter"
                    :school-type-filter="schoolTypeFilter"
                />
                <SchoolsMapFilterTech
                    v-model="schoolTechFilter"
                />
            </div>
        </div>

        <div class="relative">
            <div ref="map">
                <GoogleMap
                    api-key="AIzaSyAFbqxGQntzgzfzKFh6bArwU14MJhcV1Wc"
                    style="width: 100%; height: 700px"
                    :center="center"
                    :zoom="zoom"
                    :options="options"
                >
                    <MarkerCluster>
                        <Marker
                            v-for="(location, i) in filteredList"
                            :key="i"
                            class="relative"
                            :options="{ position: location.metadata.location }"
                            @click="handleOnClusterClick(location, i)"
                        />
                    </MarkerCluster>
                    <SchoolsMapPopup
                        v-if="toggleMapPopup"
                        :class="toggleMapPopup ? `top-[${popupY}px] left-[${popupX}px]`: ''"
                        :map-popup-name="mapPopupName"
                        :map-popup-index="mapPopupIndex"
                        @handleToggle="handleTogglePopupEmit"
                        @handleLinkToSchool="handleLinkToSchool"
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
