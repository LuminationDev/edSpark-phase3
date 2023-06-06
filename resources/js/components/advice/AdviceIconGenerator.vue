<script setup>

import {ref} from "vue";
import BaseIconGenerator from "@/js/components/global/BaseIconGenerator.vue";
import IconBookLight from "@/js/components/svg/adviceIcon/iconBookLight.vue";
import IconBookSearch from "@/js/components/svg/adviceIcon/iconBookSearch.vue";
import IconBookStars from "@/js/components/svg/adviceIcon/iconBookStars.vue";

const props = defineProps({
    iconName:{
        type: String,
        required: true
    }
})


const adviceIconComponents = {
    'iconBookLight': IconBookLight,
    'iconBookSearch': IconBookSearch,
    'iconBookStars': IconBookStars
}

</script>
<template>
    <template v-if="iconName">
        <BaseIconGenerator
            :icon-path="iconName"
            :components-object="adviceIconComponents"
        />
    </template>
</template>