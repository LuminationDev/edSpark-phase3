<script setup>

import {computed, resolveDynamicComponent} from "vue";

import {schoolColorTheme} from "@/js/constants/schoolColorTheme";

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
        default: 'navy',
    }
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

const loadColorTheme = () => {
    try {
        return `fill-[${schoolColorTheme[props.colorTheme]['med']}]`
    } catch (error) {
        console.error('Failed to load color theme:', error);
        return `fill-[${schoolColorTheme['navy']['med']}]`; //002858 
    }
};


const resolvedComponent = computed(() => {
    return resolveDynamicComponent(loadIconComponent(props.iconPath))
})

const customFill = computed(() => {
    return loadColorTheme()
})

</script>
<template>
    <component
        :is="resolvedComponent"
        class="pointer-events-none"
        :class="customFill"
    />
</template>
