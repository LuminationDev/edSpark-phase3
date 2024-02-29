<script setup>

import { computed, onMounted, resolveDynamicComponent, ref } from 'vue'

const props = defineProps({
    iconPath: {
        type: String, required: true
    },
    componentsObject: {
        type: Object, required: true
    }
})

const loadIconComponent = (iconPath) => {
    try {
        if (!Object.keys(props.componentsObject).includes(iconPath)) {
            console.log('Icon name is not recognized')
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
        <component :is="resolvedComponent" class="pointer-events-none"
            style="overflow:visible; stroke-width:0;fill:inherit;" />
</template>
