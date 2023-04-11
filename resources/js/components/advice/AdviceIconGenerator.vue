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
    'Book Light':"/components/svg/adviceIcon/iconBookLight.vue",
    'Book Search': '/components/svg/adviceIcon/iconBookSearch.vue',
    'Book Stars': '/components/svg/adviceIcon/iconBookStars.vue'

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