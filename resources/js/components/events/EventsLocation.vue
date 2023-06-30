<script setup>
import {ref, computed} from 'vue'
import {GoogleMap, Marker} from 'vue3-google-map'

const props = defineProps({
    locationType:{
        type: String,
        required: true
    }
})

console.log(props.locationType)
const emits = defineEmits([])

const mapOptions = {
    center: {
        lat: -34.910792,
        lng: 138.572068
    },
    zoom: 16,
    options : {
        zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullScreenControl: false,
        mapId: '248cf49f3df3c73d'
    }
};

</script>

<template>
    <div
        v-if="props.locationType.toLowerCase() === 'in person'"
    >
        {{ props.locationType }}
        <div class="flex w-full h-96 bg-slate-200">
            <GoogleMap
                api-key="AIzaSyAFbqxGQntzgzfzKFh6bArwU14MJhcV1Wc"
                style="width: 100%; height: 100%"
                :options="mapOptions.options"
                :center="mapOptions.center"
                :zoom="mapOptions.zoom"
            >
                <Marker
                    class="relative"
                    :options="{ position: mapOptions.center}"
                />
            </GoogleMap>
        </div>
    </div>
    <div v-else>
        {{ props.locationType }}
    </div>
</template>
