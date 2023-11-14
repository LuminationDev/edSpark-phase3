<script setup>
import {computed,  ref,  watchEffect} from 'vue';
import {useRouter} from 'vue-router';
import {GoogleMap, InfoWindow,Marker, MarkerCluster} from 'vue3-google-map'

import SchoolsMapFilterName from './SchoolsMapFilterName.vue';
import SchoolsMapFilterTech from './SchoolsMapFilterTech.vue';
import SchoolsMapFilterType from './SchoolsMapFilterType.vue';
/**
 * Components
 */
import SchoolsMapPopup from './SchoolsMapPopup.vue';

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

const schoolMatchesCriteria = (school) => {
    if (schoolNameFilter.value && !school.name.toLowerCase().includes(schoolNameFilter.value.toLowerCase())) {
        return false;
    }

    if (schoolTechFilter.value !== 'All') {
        if (!Array.isArray(school.tech_used) || !school.tech_used.some(tech => tech.name === schoolTechFilter.value)) {
            return false;
        }
    }

    if (schoolTypeFilter.value !== 'All') {
        if (!Array.isArray(school.metadata) || !school.metadata.some(item => item['schoolmeta_key'] === 'school_type' && item['schoolmeta_value'] === schoolTypeFilter.value)) {
            return false;
        }
    }

    return true;
}

const filteredList = computed(() => {
    return props.schools.filter(schoolMatchesCriteria);
});


const popupX = ref('');
const popupY = ref('');
const mapPopupIndex = ref(null);
const mapPopupName = ref('');
const mapPopupInfo = ref({});
const infoWindow = ref(null)

/**
 * Map methods
 */
const handleOnClusterClick = (location, index) => {
    if(infoWindow.value){
        infoWindow.value.forEach(window => {
            window.infoWindow.close() // EUREKA
        })
    }
    mapPopupIndex.value = location.id;
    mapPopupName.value = location.name;
    mapPopupInfo.value = location;

    popupX.value = event.clientX;
    popupY.value = event.clientY;
};

const handleTogglePopupEmit = () => {
    // toggleMapPopup.value = !toggleMapPopup.value;
};

const handleFilterBarClick = () => {
    showFilters.value = !showFilters.value;
};

const handleLinkToSchool = (schoolName) => {
    router.push({
        name: 'school-single',
        params: {
            name: schoolName
        }
    });

};

</script>

<template>
    <div
        ref="gMapParent"
        class="border border-[#0072DA] relative w-full"
    >
        <div class="bg-[#0072DA] flex justify-between flex-row h-[72px] place-items-center px-[48px] py-[48px] relative z-50">
            <h1 class="font-bold text-white text-xl md:!text-3xl">
                Search for Schools
            </h1>

            <button
                class="bg-white px-8 py-3 text-[#0072DA] w-[120px]"
                @click="handleFilterBarClick"
            >
                {{ (showFilters ? 'Close' : 'Filters') }}
            </button>
        </div>

        <div
            class="absolute top-0 bg-[#0072DA] p-6 transition-all w-full z-40 px-[48px] pt-[48px]"
            :class="showFilters ? 'h-auto md:h-[200px] mt-[20px]' : '!h-0 opacity-0 pointer-events-none' "
        >
            <div class="flex flex-col flex-wrap gap-6 mt-12 md:flex-row">
                <SchoolsMapFilterName
                    v-model="schoolNameFilter"
                />

                <SchoolsMapFilterType
                    v-model="schoolTypeFilter"
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
                    :center="mapOptions.center"
                    :zoom="mapOptions.zoom"
                    :options="mapOptions.options"
                    @click="showFilters = false"
                    @drag="showFilters = false"
                >
                    <MarkerCluster>
                        <Marker
                            v-for="(school, i) in filteredList"
                            :key="i"
                            :options="{ position: school.location, title: school.name }"
                            @click="handleOnClusterClick(school, i)"
                        >
                            <InfoWindow ref="infoWindow">
                                <SchoolsMapPopup
                                    :school-data="school"
                                    :map-popup-name="mapPopupName"
                                    :map-popup-info="mapPopupInfo"
                                    @handle-toggle="handleTogglePopupEmit"
                                    @handle-link-to-school="() => handleLinkToSchool(school.name)"
                                />
                            </InfoWindow>
                        </Marker>
                    </MarkerCluster>
                </GoogleMap>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.gm-ui-hover-effect {
    top: 0 !important;
    right: 12px !important;

    span {
        width: 30px !important;
        height: 30px !important;
    }
}
.gm-style-iw.gm-style-iw-c{
    padding: 0 0 0 0 !important;
    .gm-style-iw-d{
        padding: 0 0 0 0 !important;
        -ms-overflow-style: none !important;   /* IE and Edge */
        scrollbar-width: none !important;  /* Firefox */
    }
    .gm-style-iw-d::-webkit-scrollbar {
        display: none !important;
    }
}
.TechIconListCategory{
    font-size: 22px !important;
}

.TechIconListDescription{
    font-size: 18px;
}
</style>