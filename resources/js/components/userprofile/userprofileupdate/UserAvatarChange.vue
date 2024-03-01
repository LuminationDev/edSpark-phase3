<script setup lang="ts">
import {useVuelidate} from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import axios from "axios";
import {storeToRefs} from "pinia";
import {computed, onMounted, onUnmounted, reactive, ref, watch, watchEffect} from "vue";
import {toast} from "vue3-toastify";

import CustomErrorMessages from "@/js/components/feedbackform/CustomErrorMessages.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {avatarUIFallbackURL, imageURL} from "@/js/constants/serverUrl";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({

    sendImageUploadInstance: {
        type:Boolean,
        required: true,
        default: false
    }
})


const imageError = ref(false)
const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore);
const logoEditFile = ref(null)
const logoPreview = ref(null)
const emits = defineEmits(['sendHandleFileDroppedInstance','resetImageUploadBoolean'])
const addImageURL = (itemUrl) => {
    return imageURL + "/" + itemUrl
}


const fileDropped = ref(false)
let logoDataURL

const handleLogoUpload = (event) => {
    console.log(event.target.files)
    const file = event.target.files[0]
    if (file) {
        logoEditFile.value = file
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = (event) => {
            logoPreview.value.setAttribute('src', event.target.result)
            logoDataURL = event.target.result


        };
        fileDropped.value = true
    } else {
        // No file is selected
        fileDropped.value = false;
    }
    emits("sendHandleFileDroppedInstance", fileDropped.value)
}

const avatarUrl = computed(() => {
    const meta = currentUser.value?.metadata?.find(m => m.user_meta_key === 'userAvatar')
    return meta ? meta.user_meta_value[0].replace(/\\\//g, "/") : ''
})

const avatarUrlWithFallback  = computed(() =>{
    if(imageError.value){
        return avatarUIFallbackURL + currentUser.value.display_name
    } else{
        return `${imageURL}/${avatarUrl.value}\``
    }
})
const handleImageLoadError = () => {
    imageError.value = true
}

const isLoading = ref(false)
const uploadError = ref("")



const handleSubmitImage = () => {

    const data = new FormData()
    data.append('userAvatar',logoEditFile.value )
    return axios.post(API_ENDPOINTS.USERAVATAR.UPDATE_OR_CREATE_USER_AVATAR + currentUser.value.id, data)
        .then(res => {
            console.log(res.data.data)
            userStore.fetchCurrentUserAndLoadIntoStore()
            toast.success("Successfully submitted the image file")
            emits('resetImageUploadBoolean')
            fileDropped.value = true
        })
        .catch(err => {
            uploadError.value = err.message
            fileDropped.value = false
        })
        .finally(() => {
            isLoading.value = false
        })
}

watch(() => props.sendImageUploadInstance, (newValue, oldValue) => {
    if (newValue === true && oldValue === false) {
        handleSubmitImage()
    }
})


</script>

<template>
    <div class="flex flex-row">
        <div class="flex flex-col gap-2 px-2 w-full">
            <div class="flex flex-row w-full">
                <img
                    ref="logoPreview"
                    :src="avatarUrlWithFallback"
                    alt="profile picture"
                    class="cursor-pointer h-16 mr-6 rounded-full w-16"
                    @error="handleImageLoadError"
                >
                <div class="flex justify-center items-center gap-2 w-full">
                    <label
                        for="dropzone-file"
                        class="
                            bg-gray-10
                            dark:bg-gray-700
                            dark:hover:bg-bray-800
                            dark:hover:bg-gray-600
                            hover:bg-gray-50
                            border-2
                            dark:border-gray-600
                            dark:hover:border-gray-500
                            border-dashed
                            border-gray-300
                            cursor-pointer
                            flex
                            justify-center
                            items-center
                            flex-col
                            h-36
                            rounded-lg
                            w-full
                            "
                    >
                        <div class="flex justify-center items-center flex-col pb-6 pt-5">
                            <svg
                                class="h-8 mb-4 text-gray-500 dark:text-gray-400 w-8"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 20 16"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
                                />
                            </svg>
                            <p class="mb-2 text-center dark:text-gray-400 text-gray-500 text-sm"><span
                                class="font-semibold"
                            >Click to upload</span></p>
                            <p class="text-center dark:text-gray-400 text-gray-500 text-xs">SVG, PNG or JPG (max. 200px x 200px)</p>
                        </div>
                        <input
                            id="dropzone-file"
                            type="file"
                            class="hidden"
                            @change="handleLogoUpload"
                        >
                    </label>
                </div>
                <!--                <button-->
                <!--                    class="border-2 border-black"-->
                <!--                    @click="handleSubmitImage"-->
                <!--                >-->
                <!--                    Submit Image-->
                <!--                </button>-->
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
