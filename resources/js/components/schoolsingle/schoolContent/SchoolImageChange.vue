<script setup>
import {ref} from "vue";

let logoEditFile = ref(null)
let coverImageEditFile = ref(null)

let logoPreview = ref(null)
let coverImagePreview = ref(null)

const emits = defineEmits(['sendUploadedPhotoToContent'])
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
    <div class="flex flex-col mr-8 mb-4 pt-2 pb-4 px-2 border-2 rounded-xl">
        <h3>Replace your School's cover image and logo by uploading a new one</h3>
        <div class="flex flex-row">
            <div class="flex flex-col px-2 ">
                <label
                    for="logoUpload"
                    class="mt-2 text-sm font-bold"
                > School Logo</label>
                <input
                    id="logoEditUpload"
                    ref="logoEditFile"
                    type="file"
                    class="!border-0"
                    @change="handleLogoUpload"
                >
                <img
                    v-if="logoEditFile"
                    ref="logoPreview"
                    class="w-36 h-36 self-center"
                >
            </div>
            <div class="flex flex-col border-0 px-2">
                <label
                    for="coverImageUpload"
                    class="mt-2 text-sm font-bold"
                > School Cover Image</label>
                <input
                    id="coverImageEditUpload"
                    ref="coverImageEditFile"
                    class="!border-0 "
                    type="file"
                    @change="handleCoverImageUpload"
                >
                <img
                    v-if="coverImageEditFile"
                    ref="coverImagePreview"
                    class="w-56 h-36 self-center"
                >
            </div>
        </div>
    </div>
</template>