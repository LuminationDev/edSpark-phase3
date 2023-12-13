<script setup>
import {computed, onMounted, ref} from 'vue'
import {GoogleMap, Marker} from 'vue3-google-map'

const props = defineProps({
    locationType: {
        type: String,
        required: true
    },
    locationInfo: {
        type: Object,
        required: true
    }
})

console.log(props.locationInfo)
const emits = defineEmits([])
const eventMapRef = ref(null)

const mapOptions = {
    center: {
        lat: -34.910792,
        lng: 138.572068
    },
    zoom: 16,
    options: {
        zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullScreenControl: false,
        mapId: '248cf49f3df3c73d'
    }
};

const markerCenter = ref({
    lat: 0,
    lng: 0
})

onMounted(() => {
    if (props.locationInfo.length && (props.locationType.toLowerCase() === 'in person' || props.locationType.toLowerCase() === 'hybrid') && props.locationInfo.address) {
        const {address} = props.locationInfo
        axios.get(`https://geocode.maps.co/search?q={${address}}`).then(res => {
            if (res.data[0]['lat'] && res.data[0]['lon']) {
                if (eventMapRef.value?.ready) {
                    const newCenter = {
                        lat: Number(res.data[0]['lat']),
                        lng: Number(res.data[0]['lon'])
                    }
                    eventMapRef.value.map.panTo(newCenter);
                    markerCenter.value = newCenter;
                }
            }
        })
    }
})
const formattedUrl = computed(() => {
    return props.locationInfo.url.includes('http') ? props.locationInfo.url : 'https://' + props.locationInfo.url
})


</script>

<template>
    <div v-if="props.locationType && props.locationType.toLowerCase() === 'in person'">
        <div class="bg-blue-900 border-2 border-black p-4 text-white">
            <div class="EventAddressTitle">
                You can attend this event in person at
            </div>
            <div class="eventAddress font-semibold">
                {{ props.locationInfo.address }}
            </div>
        </div>
        <div class="bg-slate-200 flex h-96 w-full">
            <GoogleMap
                ref="eventMapRef"
                api-key="AIzaSyAFbqxGQntzgzfzKFh6bArwU14MJhcV1Wc"
                style="width: 100%; height: 100%"
                :options="mapOptions.options"
                :center="mapOptions.center"
                :zoom="mapOptions.zoom"
            >
                <Marker
                    class="relative"
                    :options="{ position: markerCenter }"
                />
            </GoogleMap>
        </div>
    </div>
    <div v-else-if="props.locationType && props.locationType.toLowerCase() === 'virtual'" />
    <div v-else-if="props.locationType && props.locationType.toLowerCase() === 'hybrid'">
        <template v-if="props.locationInfo.address">
            <div class="bg-blue-900 border-2 border-black p-4 text-white">
                <div class="EventAddressTitle">
                    You can attend this event in person at
                </div>
                <div class="eventAddress font-semibold">
                    {{ props.locationInfo.address }}
                </div>
            </div>
            <div class="bg-slate-200 flex h-96 w-full">
                <GoogleMap
                    ref="eventMapRef"
                    api-key="AIzaSyAFbqxGQntzgzfzKFh6bArwU14MJhcV1Wc"
                    style="width: 100%; height: 100%"
                    :options="mapOptions.options"
                    :center="mapOptions.center"
                    :zoom="mapOptions.zoom"
                >
                    <Marker
                        class="relative"
                        :options="{ position: markerCenter }"
                    />
                    ¬
                </GoogleMap>
            </div>
        </template>

        <div
            v-if="props.locationInfo.url"
            class="bg-blue-900 border-2 border-black p-4 text-white"
        >
            <div class="EventUrlTitle">
                Or join via the link below
            </div>
            <div class="VirtualEventLocationURL">
                <a
                    :href="formattedUrl"
                    class="cursor-pointer font-semibold"
                >{{ props.locationInfo.url }}</a>
            </div>
        </div>
    </div>
</template>


