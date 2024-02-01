<script setup>
import {computed, onMounted,ref} from 'vue'

import Profile from "@/js/components/svg/Profile.vue";
import {avatarUIFallbackURL, imageURL} from "@/js/constants/serverUrl";

const props = defineProps({
    authorLogoUrl:{
        type: String,
        required: true

    },
    authorName:{
        type: String,
        required: true
    }
})
const imageError = ref(false)

const handleImageLoadError = () => {
    imageError.value = true
}

const avatarUrlWithFallback  = computed(() =>{
    if(imageError.value){
        return avatarUIFallbackURL + props.authorName
    } else{
        return `${imageURL}/${props.authorLogoUrl}`
    }
})




</script>

<template>
    <img
        id="smallPartnerLogoImage"
        :src="avatarUrlWithFallback"
        :alt="'author logo'"
        class="bg-center bg-white h-16 object-contain rounded-full text-xs w-16"
        @error=" handleImageLoadError"
    >
</template>
<style scoped>
:deep(path){
    fill: #319795;
}
</style>