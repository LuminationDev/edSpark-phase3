<script setup lang="ts">

import {computed, ref} from "vue";

import CircleSegment from "@/js/components/dma/CircleSegment.vue";

const props = defineProps({
    size: {
        type: Number,
        default: 100,
    },
    scores: {
        type: Array<any>, // TODO structured type: { domain, category, score }
        required: true,
    }
});

const chartRef = ref(null);
const tooltipRef = ref(null);
const tooltipTarget = ref(null);

const TAU = 2 * Math.PI;

//full radius of the circle to draw
const radius = props.size / 2;
const num_radii_to_skip = 1;
// angular and radial space between the wedges
const padding = 0.5;
// offset for the start angle
const angular_offset = TAU / 4;
// number of radial elements
const num_radii = 4;
// number of circumferential elements
const num_angles = props.scores.length;

const num_radii_actual = num_radii + num_radii_to_skip;
const segments = computed(() => {
    const arr = [];
    for (let seg = 0; seg < num_angles; seg++) {
        for (let rad_index = 1; rad_index <= num_radii; ++rad_index) {
            const rad = rad_index + num_radii_to_skip;
            const clazz = rad_index <= props.scores[seg].score ? props.scores[seg].domain : 'white';
            arr.push({
                id: `${seg}_${rad}`,
                category: props.scores[seg].category,
                inner_radius: ((radius * (rad - 1)) / (num_radii_actual)) + (padding/2),
                outer_radius: ((radius * rad) / num_radii_actual) - (padding/2),
                start_angle: (seg * TAU / num_angles) - angular_offset,
                delta_angle: (TAU / num_angles),
                x_offset: 0,
                y_offset: 0,
                class: clazz
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

    tooltipRef.value.style.left = `${targetRect.left - screenRect.left +( targetRect.width / 2)}px`;
    tooltipRef.value.style.bottom = `${(screenRect.bottom - (targetRect.top))}px`;
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
            <g mask="url(#line_mask)">
                <CircleSegment
                    v-for="segment in segments"
                    :key="segment.id"
                    :class="segment.class"
                    :label="segment.category"
                    :inner_radius="segment.inner_radius"
                    :outer_radius="segment.outer_radius"
                    :start_angle="segment.start_angle"
                    :delta_angle="segment.delta_angle"
                    :x_offset="segment.x_offset"
                    :y_offset="segment.y_offset"
                    @mouseover="handleShowTooltip"
                    @mouseout="handleHideTooltip"
                />
            </g>

            <mask id="line_mask">
                <circle
                    cx="0"
                    cy="0"
                    :r="size/2"
                    fill="white"
                />
                <line
                    v-for="n of num_angles"
                    :key="n"
                    :x1="0"
                    :y1="0"
                    :x2="size * Math.cos((TAU * n / num_angles)- angular_offset) /2"
                    :y2="size * Math.sin((TAU * n / num_angles) - angular_offset) /2 "
                    :stroke-width="padding"
                    stroke="black"
                    fill="black"
                />
            </mask>
        </svg>
        <div
            ref="tooltipRef"
            class="absolute bg-black category-tooltip font-semibold hidden p-3 rounded text-center text-small text-white uppercase"
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
    left: 50%;
    bottom: -5px;
    transform: translateX(-50%) rotate(45deg);
}

.category-tooltip {
    transform: translateX(-50%)
}

.teaching {
    fill: var(--color-blue);
}

.learning {
    fill: var(--color-cyan);
}

.leading {
    fill: var(--color-red);
}

.managing {
    fill: var(--color-orange);
}

.white {
    fill: white;
}

</style>
