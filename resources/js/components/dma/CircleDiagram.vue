<script setup lang="ts">

import {computed, ref} from "vue";

import CircleSegment from "@/js/components/dma/CircleSegment.vue";

const props = defineProps({
    size: {
        type: Number,
        default: 100,
    },
    scores: {
        type: Array<any>, // TODO structured type: { domain, element, score, selected }
        required: true,
    },
    clickable: {
        type: Boolean,
        default: false,
    }
});

const emit = defineEmits(['click']);

const chartRef = ref(null);
const tooltipRef = ref(null);
const tooltipTarget = ref(null);
const hoverElement = ref(null);

const TAU = 2 * Math.PI;

//full radius of the circle to draw
const radius = props.size / 2;
const num_radii_to_skip = 1;
// angular and radial space between the wedges
const padding = 0.33;
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
            const clazz = [
                props.scores[seg].domain,
                {
                    'empty': rad_index > props.scores[seg].score,
                    'cursor-pointer': props.clickable,
                    'brightness-90': props.scores[seg].highlighted || hoverElement.value === props.scores[seg].element,
                    'highlight': props.scores[seg].selected
                }
            ];
            arr.push({
                id: `${seg}_${rad}`,
                element: props.scores[seg].element,
                element_index: seg,
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

    if(props.clickable) {
        hoverElement.value = event.target.dataset.label;
    }
}
const handleHideTooltip = () => {
    tooltipTarget.value = null;
    tooltipRef.value.style.display = 'none';
    hoverElement.value = null;
}

const handleClickSegment = (segment) => {
    emit('click',props.scores[segment.element_index]);
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
                    :label="segment.element"
                    :inner_radius="segment.inner_radius"
                    :outer_radius="segment.outer_radius"
                    :start_angle="segment.start_angle"
                    :delta_angle="segment.delta_angle"
                    :x_offset="segment.x_offset"
                    :y_offset="segment.y_offset"
                    @mouseover="handleShowTooltip"
                    @mouseout="handleHideTooltip"
                    @click="handleClickSegment(segment)"
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
            class="absolute bg-black element-tooltip font-semibold hidden p-3 rounded text-center text-small text-white uppercase"
        >
            {{ tooltipTarget?.dataset.label }}
        </div>
    </div>
</template>
<style scoped lang="scss">

.element-tooltip::before {
    content: "";
    background: black;
    position: absolute;
    width: 10px;
    height: 10px;
    left: 50%;
    bottom: -5px;
    transform: translateX(-50%) rotate(45deg);
}

.element-tooltip {
    transform: translateX(-50%)
}

.teaching {
    fill: var(--color-blue);
    &.highlight {
      fill: var(--color-blue-dark);
      &.empty {
        fill: var(--color-blue-pale);
      }
    }
}

.learning {
    fill: var(--color-cyan);
    &.highlight {
      fill: var(--color-cyan-dark);
      &.empty {
        fill: var(--color-cyan-pale);
      }
    }
}

.leading {
    fill: var(--color-red);
    &.highlight {
      fill: var(--color-red-dark);
      &.empty {
        fill: var(--color-red-pale);
      }
    }
}

.managing {
    fill: var(--color-orange);
    &.highlight {
      fill: var(--color-orange-dark);
      &.empty {
        fill: var(--color-orange-pale);
      }
    }
}

.empty {
    fill: white;
}

</style>
