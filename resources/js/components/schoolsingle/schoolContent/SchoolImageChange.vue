<script setup>
import {onMounted, ref} from "vue";

import {imageURL} from "@/js/constants/serverUrl";

const props = defineProps({
    currentLogo:{
        type: String,
        required: false,
        default: ''
    },
    currentCoverImage:{
        type: String,
        required: false,
        default: ''
    }
})


const logoEditFile = ref(null)
const coverImageEditFile = ref(null)

const logoPreview = ref(null)
const coverImagePreview = ref(null)

const emits = defineEmits(['sendUploadedPhotoToContent'])

const addImageURL = (itemUrl) => {
    return imageURL+ "/" + itemUrl
}

onMounted(() =>{
    if(props.currentLogo){
        logoPreview.value.setAttribute('src', addImageURL(props.currentLogo))
        console.log(addImageURL(props.currentLogo))
    }
    if(props.currentCoverImage){
        coverImagePreview.value.setAttribute('src',addImageURL(props.currentCoverImage))
    }

})

const handleLogoUpload = (event) =>{
    logoEditFile.value = event.target.files[0]
    const reader = new FileReader()
    reader.readAsDataURL(logoEditFile.value)
    reader.onload = (event) =>{
        logoPreview.value.setAttribute('src', event.target.result)
    }
    emits('sendUploadedPhotoToContent', 'logo', logoEditFile.value)
}
const handleCoverImageUpload = (event) => {
    coverImageEditFile.value = event.target.files[0]
    const reader = new FileReader()
    reader.readAsDataURL(coverImageEditFile.value)
    reader.onload = (event) =>{
        coverImagePreview.value.setAttribute('src', event.target.result)
    }
    emits('sendUploadedPhotoToContent', 'coverImage', coverImageEditFile.value)


}
</script>
<template>
    <div class="border-2 flex flex-col mb-4 mr-8 pb-4 pt-2 px-2 rounded-xl">
        <div class="flex flex-row">
            <div class="flex flex-col gap-2 px-2">
                <label
                    for="logoUpload"
                    class="font-semibold mt-2 text-center text-lg"
                >Logo</label>
                <!--                <input-->
                <!--                    id="logoEditUpload"-->
                <!--                    ref="logoEditFile"-->
                <!--                    type="file"-->
                <!--                    class="!border-0"-->
                <!--                    @change="handleLogoUpload"-->
                <!--                >-->
                <div class="flex justify-center items-center w-full  gap-2">
                    <label
                        for="dropzone-file"
                        class="
                            bg-gray-50
                            dark:bg-gray-700
                            dark:hover:bg-bray-800
                            dark:hover:bg-gray-600
                            hover:bg-gray-100
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
                            <p class="text-center dark:text-gray-400 text-gray-500 text-xs">SVG, PNG, JPG or GIF</p>
                        </div>
                        <input
                            id="dropzone-file"
                            ref="logoEditFile"
                            type="file"
                            class="hidden"
                            @change="handleLogoUpload"
                        >
                    </label>
                </div>

                <img
                    ref="logoPreview"
                    class="h-36 self-center w-36"
                >
            </div>
            <div class="border-0 flex flex-col gap-2 px-2">
                <label
                    for="coverImageUpload"
                    class="font-semibold mt-2 text-center text-lg"
                >Cover Image</label>
                <!--                <input-->
                <!--                    id="coverImageEditUpload"-->
                <!--                    ref="coverImageEditFile"-->
                <!--                    class="!border-0"-->
                <!--                    type="file"-->
                <!--                    @change="handleCoverImageUpload"-->
                <!--                >-->
                <div class="flex justify-center items-center flex flex-col gap-2 w-full">
                    <label
                        for="dropzone-file"
                        class="
                            bg-gray-50
                            dark:bg-gray-700
                            dark:hover:bg-bray-800
                            dark:hover:bg-gray-600
                            hover:bg-gray-100
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
                            <p class="text-center dark:text-gray-400 text-gray-500 text-xs">SVG, PNG, JPG or GIF</p>
                        </div>
                        <input
                            id="dropzone-file"
                            ref="coverImageEditFile"
                            type="file"
                            class="hidden"
                            @change="handleCoverImageUpload"
                        >
                    </label>
                    <img
                        ref="coverImagePreview"
                        class="h-36 self-center w-56"
                    >
                </div>
            </div>
        </div>
    </div>
</template>