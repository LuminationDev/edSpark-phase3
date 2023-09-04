<script setup>
import Profile from "@/js/components/svg/Profile.vue";
import {imageURL} from "@/js/constants/serverUrl";
import {ref, computed, onMounted} from 'vue'

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
    <div class="flex justify-center items-center h-20 mx-4 smallPartnerLogo w-24">
        <img
            v-if="!showPlaceholderImage"
            id="smallPartnerLogoImage"
            :src="`${imageURL}/${props.authorLogoUrl}`"
            :alt="'author logo'"
            class="bg-center h-24 object-contain rounded-full text-xs w-24"
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