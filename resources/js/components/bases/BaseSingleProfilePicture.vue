<script setup>
import {computed, onMounted,ref} from 'vue'

import Profile from "@/js/components/svg/Profile.vue";
import {imageURL} from "@/js/constants/serverUrl";

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
const showPlaceholderImage = ref(false)
const handleProfileImageError = () =>{
    console.log('error liao')
    showPlaceholderImage.value = true
}

onMounted(() =>{
    document.getElementById('smallPartnerLogoImage').addEventListener('error', handleProfileImageError)
})

</script>

<template>
    <div class="">
        <img
            v-if="!showPlaceholderImage"
            id="smallPartnerLogoImage"
            :src="`${imageURL}/${props.authorLogoUrl}`"
            :alt="'author logo'"
            class="bg-center bg-white h-16 object-contain rounded-full text-xs w-16"
        >
        <Profile
            v-else
            class="h-16 rounded-full w-16"
        />
    </div>
</template>
<style scoped>
:deep(path){
    fill: #319795;
}
</style>