<script setup>

import {computed, onMounted, resolveDynamicComponent, ref} from 'vue'
import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";

const props = defineProps({
    iconPath: {
        type: String, required: true
    },
    componentsObject: {
        type: Object, required: true
    },
    colorTheme: {
        type: String,
        required: false,
        default: 'teal',
    }
})

const fillColorTheme = ref('')

onMounted(() => {
    if (schoolColorKeys.includes(props.colorTheme)) {
        fillColorTheme.value = `fill-[${schoolColorTheme[props.colorTheme]['med']}] stroke-[${schoolColorTheme[props.colorTheme]['med']}]`;
    } else {
        fillColorTheme.value = `fill-[${schoolColorTheme['teal']['med']}] stroke-[${schoolColorTheme['teal']['med']}]`;
    }
})

const customFill = computed(() => {
    return fillColorTheme.value;
})


const loadIconComponent = (iconPath) => {
    try {
        if (!Object.keys(props.componentsObject).includes(iconPath)) {
            console.log('Oh no icon name is not recognized')
        } else {
            return props.componentsObject[iconPath];
        }
    } catch (error) {
        console.error('Failed to load component:', error);
        return null;
    }
};


const resolvedComponent = computed(() => {
    return resolveDynamicComponent(loadIconComponent(props.iconPath));
})


</script>
<template>
    <component 
        :is="resolvedComponent" 
        class="pointer-events-none" 
        :class="customFill" 
        style="overflow:visible; stroke-width:0"
    />
</template>
