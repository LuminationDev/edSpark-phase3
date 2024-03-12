<script setup>
import {onMounted, ref} from "vue";

import {imageURL} from "@/js/constants/serverUrl";

const props = defineProps({
    currentLogo: {
        type: String,
        required: false,
        default: ''
    },
    currentCoverImage: {
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
    return imageURL + "/" + itemUrl
}

onMounted(() => {
    if (props.currentLogo) {
        logoPreview.value.setAttribute('src', addImageURL(props.currentLogo))
    }
    if (props.currentCoverImage) {
        coverImagePreview.value.setAttribute('src', addImageURL(props.currentCoverImage))
    }

})

const handleLogoUpload = (event) => {
    logoEditFile.value = event.target.files[0]
    const reader = new FileReader()
    reader.readAsDataURL(logoEditFile.value)
    reader.onload = (event) => {
        logoPreview.value.setAttribute('src', event.target.result)
    }
    emits('sendUploadedPhotoToContent', 'logo', logoEditFile.value)
}
const handleCoverImageUpload = (event) => {
    coverImageEditFile.value = event.target.files[0]
    const reader = new FileReader()
    reader.readAsDataURL(coverImageEditFile.value)
    reader.onload = (event) => {
        coverImagePreview.value.setAttribute('src', event.target.result)
    }
    emits('sendUploadedPhotoToContent', 'coverImage', coverImageEditFile.value)


}
</script>
<template>
    <div class="flex flex-col px-4 rounded w-4/5">
        <div class="2xl:!flex-row LogoAndCoverImageContainer flex flex-row  gap-4 lg:!flex-col">
            <div class="2xl:!w-1/2 flex flex-col gap-4 logoColumn px-2 w-1/2 w-full lg:!w-full">
                <label
                    for="logoUpload"
                    class="font-semibold mt-2 text-center text-lg"
                >Logo</label>
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
                            w-36
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
                            <p class="text-center dark:text-gray-400 text-gray-500 text-xs">SVG, PNG or JPG</p>
                            <p class="text-center dark:text-gray-400 text-gray-500 text-xs">200px * 200px </p>
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
                    class="h-28 object-contain self-center w-28"
                >
            </div>
            <div class="2xl:!w-1/2 CoverImageColumn border-0 flex flex-col px-2 w-1/2 w-full   gap-4 lg:!w-full">
                <label
                    for="coverImageUpload"
                    class="font-semibold mt-2 text-center text-lg"
                >Cover image</label>
                <div class="flex justify-center items-center flex flex-col gap-4 w-full">
                    <label
                        for="dropzone-file-1"
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
                            <p class="text-center dark:text-gray-400 text-gray-500 text-xs">SVG, PNG or JPG</p>
                            <p class="text-center dark:text-gray-400 text-gray-500 text-xs">500px * 500px </p>

                        </div>
                        <input
                            id="dropzone-file-1"
                            ref="coverImageEditFile"
                            type="file"
                            class="hidden"
                            @change="handleCoverImageUpload"
                        >
                    </label>
                    <img
                        ref="coverImagePreview"
                        class="h-28 object-contain self-center w-28"
                    >
                </div>
            </div>
        </div>
    </div>
</template>