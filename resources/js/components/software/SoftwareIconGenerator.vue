<script setup>

import {ref} from "vue";
import BaseIconGenerator from "@/js/components/global/BaseIconGenerator.vue";

const props = defineProps({
    iconName:{
        type: String,
        required: true
    }
})
const iconPath = ref('')
const adviceIconPaths = {
    'Department Approved':"/components/svg/software/DeptApprovedIcon.vue",
    'Department Provided': '/components/svg/software/DeptProvidedIcon.vue',
    'Approved and Negotiated': '/components/svg/software/DeptNegotiatedIcon.vue'

}

if(Object.keys(adviceIconPaths).includes(props.iconName)){
    iconPath.value = adviceIconPaths[`${props.iconName}`]
}

</script>
<template>
    <template v-if="iconPath">
        <BaseIconGenerator :icon-path="iconPath" />
    </template>
</template>