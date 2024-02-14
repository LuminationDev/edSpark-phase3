<script setup lang="ts">


const props = defineProps({
    x_offset: {
        type: Number,
        default: -10
    },
    y_offset: {
        type: Number,
        default: 20
    },
    inner_radius: {
        type: Number,
        default: 10
    },
    outer_radius: {
        type: Number,
        default: 40
    },
    start_angle: {
        type: Number,
        default: Math.PI / 16,
    },
    delta_angle: {
        type: Number,
        default: Math.PI / 4,
    },
    colour: {
        type: String,
        default: 'black',
    },
    label: {
        type: String,
        default: '',
    }
});

const {inner_radius, outer_radius, start_angle, delta_angle, x_offset, y_offset} = props;
const end_angle = start_angle + delta_angle;

const x1 = x_offset + (inner_radius * Math.cos(start_angle));
const y1 = y_offset + (inner_radius * Math.sin(start_angle));

const x2 = x_offset + (inner_radius * Math.cos(end_angle));
const y2 = y_offset + (inner_radius * Math.sin(end_angle));

const x3 = x_offset + (outer_radius * Math.cos(end_angle));
const y3 = y_offset + (outer_radius * Math.sin(end_angle));

const x4 = x_offset + (outer_radius * Math.cos(start_angle));
const y4 = y_offset + (outer_radius * Math.sin(start_angle));

</script>
<template>
    <path
        :d="`M ${x1} ${y1}
           A ${inner_radius} ${inner_radius} 0 0 1 ${x2} ${y2}
           L ${x3} ${y3}
           A ${outer_radius} ${outer_radius} 0 0 0 ${x4} ${y4}
           L ${x1} ${y1}
           `"
        :data-label="props.label"
    />
</template>
<style></style>
