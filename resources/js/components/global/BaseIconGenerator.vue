<script setup>

import {shallowRef} from "vue";

const props = defineProps({
    iconPath :{
        type: String, required: true
    }
})

const techIcon = shallowRef('')

import(`../../${props.iconPath}`).then(val =>
    techIcon.value = val.default
)

</script>
<template>
    <component
        :is="techIcon"
        v-if="techIcon"
        class="pointer-events-none"
    />
</template>