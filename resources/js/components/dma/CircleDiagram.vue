<script setup lang="ts">

import {computed, ref} from "vue";

import CircleSegment from "@/js/components/dma/CircleSegment.vue";

const props = defineProps({
    size: {
        type: Number,
        default: 100,
    },
    scores: {
        type: Array<any>, // TODO structured type: { category, colour, score }
        required: true,
    }
});

const chartRef = ref(null);
const tooltipRef = ref(null);
const tooltipTarget = ref(null);

const TAU = 2 * Math.PI;

const radius = props.size / 3;
const padding = 0.1;
const angular_padding = TAU / 720;
const angular_offset = TAU / 4;
const num_radii = 4;
const num_angles = props.scores.length;

const segments = computed(() => {
    const arr = [];
    for (let seg = 0; seg < num_angles; seg++) {
        for (let rad = 1; rad <= num_radii; ++rad) {
            const colour = rad <= props.scores[seg].score ? props.scores[seg].colour : 'white';
            arr.push({
                id: `${seg}_${rad}`,
                category: props.scores[seg].category,
                inner_radius: ((radius * rad) / num_radii) + padding,
                outer_radius: ((radius * (rad + 1)) / num_radii) - padding,
                start_angle: (seg * TAU / num_angles) + angular_padding - angular_offset,
                delta_angle: (TAU / num_angles) - angular_padding,
                x_offset: 0,
                y_offset: 0,
                colour
            })
        }
    }
    return arr;
})

const handleShowTooltip = (event) => {
    tooltipTarget.value = event.target;
    tooltipRef.value.style.display = 'block';

    const screenRect = chartRef.value.getBoundingClientRect();
    const targetRect = event.target.getBoundingClientRect();

    const posX = targetRect.left - screenRect.left - 75 + (targetRect.width/2);

    tooltipRef.value.style.left = `${posX}px`;
    tooltipRef.value.style.bottom = `${screenRect.bottom - targetRect.top + 5}px`;
}
const handleHideTooltip = () => {
    tooltipTarget.value = null;
    tooltipRef.value.style.display = 'none';
}

</script>
<template>
    <div
        ref="chartRef"
        class="radial-chart relative"
    >
        <svg :viewBox="`${-size/2} ${-size/2} ${size} ${size}`">
            <CircleSegment
                v-for="segment in segments"
                :key="segment.id"
                :label="segment.category"
                :inner_radius="segment.inner_radius"
                :outer_radius="segment.outer_radius"
                :start_angle="segment.start_angle"
                :delta_angle="segment.delta_angle"
                :x_offset="segment.x_offset"
                :y_offset="segment.y_offset"
                :colour="segment.colour"
                @mouseover="handleShowTooltip"
                @mouseout="handleHideTooltip"
            />
        </svg>
        <div
            ref="tooltipRef"
            class="absolute bg-black category-tooltip hidden p-3 rounded text-center text-white uppercase w-[150px]"
        >
            {{ tooltipTarget?.dataset.label }}
        </div>
    </div>
</template>
<style scoped lang="scss">
    .category-tooltip::before {
        content: "";
        background: black;
        position: absolute;
        width: 10px;
        height: 10px;
        left: 70px;
        bottom: -5px;
        transform: rotate(45deg);

    }
</style>
