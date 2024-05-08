<script setup>
import './noUiSlider.css'

import noUiSlider from 'nouislider';
import {computed, defineEmits, defineProps, onMounted, ref} from 'vue';

const props = defineProps({
    config: {
        type: Object,
        required: true
    },
    values:
        {
            type: Array,
            required: true
        },
    id:
        {
            type: String,
            required: false,
            default: () => Math.random().toString(36).substr(2, 4)
        }
});

const emit = defineEmits(['update:values']);

const sliderId = computed(() => props.id === undefined ? uniqueId() : props.id);


const slider = ref(null);
const config = ref(props.config)
const currentValues = ref([])


onMounted(() => {
    noUiSlider.create(slider.value, props.config);
    slider.value.noUiSlider.on('update', updateValue);
});

const updateValue = (value, handle) => {
    currentValues.value[handle] = value[handle];
    emit('update:values', currentValues.value);
};

const uniqueId = () => {
    const s4 = () => {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return `vue-nouislider-${s4()}${s4()}`;
};
</script>

<template>
    <div class="my-4 slider-wrapper">
        <div
            id="slider-round"
            ref="slider"
            class="vue-nouislider"
        />
    </div>
</template>


<style scoped lang="scss">
</style>
