<script setup>
import {ref, computed, onMounted, watch} from 'vue';
import axios from 'axios';
import {GoogleMap, Marker, MarkerCluster,} from 'vue3-google-map'
import {useRouter} from 'vue-router';
import {serverURL} from "@/js/constants/serverUrl";

/**
 * Components
 */
import SchoolsMapPopup from './SchoolsMapPopup.vue';
import SchoolsMapFilterName from './SchoolsMapFilterName.vue';
import SchoolsMapFilterType from './SchoolsMapFilterType.vue';
import SchoolsMapFilterTech from './SchoolsMapFilterTech.vue';

/**
 * Get some props
 */
const props = defineProps({
    schools: {
        type: Array,
        required: true
    },
    schoolsAvailable: {
        type: Boolean,
        required: true
    }
});

const router = useRouter();
const showFilters = ref(false);
const toggleMapPopup = ref(false);
// const toggleFilters = computed(() => {
//     showFilters.value = !showFilters.value;
// });

/**
 * Set the maps center point
 */
const mapOptions = ref({
    center: {
        lat: -34.9285,
        lng: 138.6007
    },
    zoom: 12,
    options: {
        zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullScreenControl: false,
        mapId: '164f2a0469c00794'
    }
});

/**
 * Map markers and filters
 */
const schoolNameFilter = ref('');
const schoolTypeFilter = ref('All');
const schoolTechFilter = ref('All');

const schoolsArray = ref(props.schools);
// const filteredList = ref([]);

const filteredList = computed({
    get() {
        return props.schools;
    },

    set(newValue) {
        console.log(newValue)

        schoolsArray.value = newValue;
    }
});

/**
 * Handle filter
 */
const handleFilterName = (value) => {
    filteredList.value = filteredList.value.filter(school => school.name.toLowerCase().includes(value.toLowerCase()));
};

const handleFilterTech = (value) => {
    if (value === 'All') {
        filteredList.value = filteredList.value;
    } else {
        filteredList.value = filteredList.value.filter(
            school => school.tech_used.some(
                tech => tech.name === value
            )
        )
    }
    ;
};


const handleFilterType = (value) => {
    console.log(value);
    if (value === 'All') {
        filteredList.value = filteredList.value.value;
    } else {
        filteredList.value = filteredList.value.filter(
            school => school.metadata.find(
                item => item.schoolmeta_value === value
            )
        )
    }
    ;
};

const popupX = ref('');
const popupY = ref('');
const mapPopupIndex = ref(null);
const mapPopupName = ref('');
const mapPopupInfo = ref({});

/**
 * Map methods
 */
const handleOnClusterClick = (location, index) => {
    if (toggleMapPopup.value) {
        toggleMapPopup.value = false;
    }
    ;

    toggleMapPopup.value = !toggleMapPopup.value;
    mapPopupIndex.value = location.id;
    mapPopupName.value = location.name;
    mapPopupInfo.value = location;

    popupX.value = event.clientX / 2;
    popupY.value = event.clientY / 2;
};

const handleTogglePopupEmit = () => {
    toggleMapPopup.value = !toggleMapPopup.value;
};

const handleFilterBarClick = () => {
    showFilters.value = !showFilters.value;
};

const handleLinkToSchool = () => {
    props.schools.forEach(school => {
        const idMatch = school.id;
        console.log(idMatch);
        if (idMatch === mapPopupIndex.value) {
            let schoolUrlFriendly = school.name.replace(/\s+/g, '-').toLowerCase();
            router.push({
                name: 'school-single',
                params: {
                    name: school.name
                }
            });
        }
    });
};

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
                    @handleEmitSearchName="handleFilterName"
                />

                <SchoolsMapFilterType
                    v-model="schoolTypeFilter"
                    @handleEmitTypeFilter="handleFilterType"
                />
                <SchoolsMapFilterTech
                    v-model="schoolTechFilter"
                    @handleEmitTechFilter="handleFilterTech"
                />
            </div>
        </div>

        <div class="relative">
            <div ref="map">
                <GoogleMap
                    api-key="AIzaSyAFbqxGQntzgzfzKFh6bArwU14MJhcV1Wc"
                    style="width: 100%; height: 700px"
                    :center="mapOptions.center"
                    :zoom="mapOptions.zoom"
                    :options="mapOptions.options"
                >
                    <MarkerCluster>
                        <Marker
                            v-for="(school, i) in filteredList"
                            :key="i"
                            class="relative"
                            :options="{ position: school.location }"
                            @click="handleOnClusterClick(school, i)"
                        />
                    </MarkerCluster>
                    <SchoolsMapPopup
                        v-if="toggleMapPopup"
                        :class="toggleMapPopup ? `top-[${popupY}px] left-[${popupX}px]`: ''"
                        :map-popup-name="mapPopupName"
                        :map-popup-index="mapPopupIndex"
                        :map-popup-info="mapPopupInfo"
                        @handleToggle="handleTogglePopupEmit"
                        @handleLinkToSchool="handleLinkToSchool"
                    />
                </GoogleMap>
            </div>
        </div>
    </div>
</template>
