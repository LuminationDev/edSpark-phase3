<script setup>
import {ref, computed, onMounted, watch, watchEffect} from 'vue';
import axios from 'axios';
import {GoogleMap, Marker, MarkerCluster, InfoWindow} from 'vue3-google-map'
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

const filteredListId = computed(() => {
    if (!schoolNameFilter.value && schoolTypeFilter.value === 'All' && schoolTechFilter.value === 'All') {
        console.log('inside all filter is empty return all')
        return props.schools.map(school => school.id)
    }

    return Array.from(new Set([...idsFromName.value, ...idsFromTech.value, ...idsFromType.value]))
})

const filteredList = computed(() => {
    console.log(filteredListId.value)
    if (filteredListId.value) {
        return props.schools.filter(school => filteredListId.value.includes(school.id))
    } else {
        return []
    }
})

/**
 * Handle filter
 */
const idsFromName = computed(() => {
    console.log('inside ids from name computed')
    if (!schoolNameFilter.value) {
        // console.log(props.schools.map(school => {
        //     return school.id
        // }))
        return []
    } else {
        return props.schools.filter(school => school.name.toLowerCase().includes(schoolNameFilter.value.toLowerCase())).map(school => school.id);
    }

})

const idsFromTech = computed(() => {
    if (schoolTechFilter.value === 'All') {
        // console.log(props.schools.map(school => {
        //     return school.id
        // }))
        return []
    } else {
        return props.schools.filter(
            school => {
                if (Array.isArray(school.tech_used)) {
                    return school.tech_used.some(
                        tech => tech.name === schoolTechFilter.value
                    )

                } else {
                    return false
                }
            }
        ).map(school => school.id)
    }
})

const idsFromType = computed(() => {
    if (schoolTypeFilter.value === 'All') {
        // console.log(props.schools.map(school => {
        //     return school.id
        // }))
        return []
    } else {
        return props.schools.filter(
            school => {
                if (Array.isArray(school.metadata)) {
                    return school.metadata.some(
                        item => {
                            console.log(item)
                            if (item['schoolmeta_key'] === 'school_type') {
                                return item['schoolmeta_value'] === schoolTypeFilter.value
                            } else {
                                return false
                            }
                        }
                    )
                } else {
                    return false
                }
            }
        ).map(school => school.id)
    }
})


const popupX = ref('');
const popupY = ref('');
const mapPopupIndex = ref(null);
const mapPopupName = ref('');
const mapPopupInfo = ref({});
const infoWindow = ref(null)

watchEffect(() => {
    if(infoWindow.value){
        console.log(infoWindow.value)
    } else{
        console.log('hmm info window is null')
    }
})
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

const handleChangeInfoWindows = () => {
    console.log('inside handel infoWindwos')
}

</script>

<template>
    <div
        ref="gMapParent"
        class="border border-[#0072DA] relative w-full"
    >
        <div class="bg-[#0072DA] flex justify-between flex-row h-[72px] place-items-center px-[48px] relative z-50">
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
            class="absolute top-0 bottom-0 bg-[#0072DA] p-6 transition-all w-full z-40"
            :class="showFilters ? 'h-[200px]' : '!h-0 opacity-0 pointer-events-none' "
        >
            <div class="flex flex-row flex-wrap gap-6 mt-12">
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
                                    :data="school"
                                    :map-popup-name="mapPopupName"
                                    :map-popup-info="mapPopupInfo"
                                    @handle-toggle="handleTogglePopupEmit"
                                    @handle-link-to-school="handleLinkToSchool"
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