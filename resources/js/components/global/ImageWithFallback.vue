<script setup>
import {computed,ref} from 'vue'

const props = defineProps({
    imageType:{
        type: String,
        required: true
    },
    imageUrl:{
        type: String,
        required: true
    },
    imageAlt:{
        type: String,
        required:true
    }
})
const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;
const imageError = ref(false)


const handleImageLoadError = () => {
    imageError.value = true
}

const fallbackUrl = () => {
    switch (props.imageType) {
    case 'profile':
        return `${imageURL}/uploads/image/profile.jpg`
    case 'catalogue':
        return `${imageURL}/uploads/image/product.png`
    default:
        return `${imageURL}/uploads/image/profile.jpg`
    }
}
const urlWithFallback = computed(() => {
    if (imageError.value) {
        return fallbackUrl()
    } else {
        return props.imageUrl
    }
})

</script>

<template>
    <img
        :src="urlWithFallback"
        :alt="props.imageAlt"
        @error="handleImageLoadError"
    >
</template>
