<script setup lang="ts">

import {computed} from "vue";
import CircleSegment from "@/js/components/dma/CircleSegment.vue";

const props = defineProps({
    size: {
        type: Number,
        default: 100,
    },
});

const TAU = 2 * Math.PI;

//todo: Adjust the below to consume data to show off the user's completion
const radius = props.size / 3;
const padding = 0.1;
const angular_padding = TAU / 720;
const num_radii = 3;
const num_angles = 30;
const colours = ['red', 'blue', 'green', 'yellow'];
const segments = computed(() => {
    const arr = [];
    for (let seg = 0; seg < num_angles; seg++) {
        const colour = colours[seg % colours.length]
        for (let rad = 1; rad <= num_radii; ++rad) {
            arr.push({
                inner_radius: ((radius * rad) / num_radii) + padding,
                outer_radius: ((radius * (rad + 1)) / num_radii) - padding,
                start_angle: (seg * TAU / num_angles) + angular_padding,
                delta_angle: (TAU / num_angles) - angular_padding,
                x_offset: 0,
                y_offset: 0,
                colour
            })
        }
    }
    return arr;
})

console.log(segments.value);

</script>
<template>
    <div class="radial-chart">
        <svg :viewBox="`${-size/2} ${-size/2} ${size} ${size}`">
            <CircleSegment
                v-for="segment in segments"
                :inner_radius="segment.inner_radius"
                :outer_radius="segment.outer_radius"
                :start_angle="segment.start_angle"
                :delta_angle="segment.delta_angle"
                :x_offset="segment.x_offset"
                :y_offset="segment.y_offset"
                :colour="segment.colour"
            />
        </svg>
    </div>
</template>
<style></style>
