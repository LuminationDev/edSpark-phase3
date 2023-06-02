<script setup>

import {computed, resolveDynamicComponent, shallowRef} from "vue";

const props = defineProps({
    iconPath :{
        type: String, required: true
    },
    componentsObject: {
        type: Object, required: true
    }
})
// const techIcon = shallowRef('')

// import(`../../${props.iconPath}`).then(val =>
//     techIcon.value = val.default
// )


const loadIconComponent =  (iconPath) => {
    try {
        return props.componentsObject[iconPath];
    } catch (error) {
        console.error('Failed to load component:', error);
        return null;
    }
};

// techIcon.value = loadIconComponent(props.iconPath)

const resolvedComponent = computed(() => {
    console.log('resolced vomponent is here ehhehhe triggered')
    return resolveDynamicComponent(loadIconComponent(props.iconPath))
})


</script>
<template>
    <component
        :is="resolvedComponent"
        class="pointer-events-none"
    />
</template>
