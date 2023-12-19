<script setup lang="ts">
import axios from 'axios'
import {computed, onMounted, ref, watch} from "vue";

import Loader from './loader/index.vue';

const props = defineProps({
    server: {
        type: String,
        default: '/api/upload'
    },
    isInvalid: {
        type: Boolean,
        default: false
    },
    media: {
        type: Array,
        default: () => []
    },
    location: {
        type: String,
        default: ''
    },
    max: {
        type: Number,
        default: null
    },
    maxFilesize: {
        type: Number,
        default: 4
    },
    warnings: {
        type: Boolean,
        default: true
    }
})

const addedMedia = ref([]);
const savedMedia = ref([]);
const removedMedia = ref([]);
const isLoading = ref(true);

const allMedia = computed(() => [...savedMedia.value, ...addedMedia.value]);

const emits = defineEmits([
    'init',
    'change',
    'add',
    'remove',
    'max',
    'maxFilesize'
]);

const handleEmitInit = (allMedia): void => {
    emits('init', allMedia)
}
const handleEmitChange = (allItem): void => {
    emits('change', allItem)
}
const handleEmitAdd = (item, target): void => {
    emits('add', item, target)
}
const handleEmitRemove = (item, target): void => {
    emits('remove', item, target)
}
const handleEmitMax = (itemCount): void => {
    emits('max', itemCount)
}
const handleEmitMaxFileSize = (itemSize): void => {
    emits('maxFilesize', itemSize)
}


const init = () => {
    addedMedia.value = [...props.media]
    addedMedia.value.forEach((image, index) => {
        if (!addedMedia.value[index].url) {
            addedMedia.value[index].url = props.location + "/" + image.name
        }
    })
    setTimeout(() => isLoading.value = false, 1000)
    handleEmitInit(allMedia)
}

onMounted(() => {
    init();
})

const fileChange = async (event) => {
    isLoading.value = true;
    const files = event.target.files;

    for (let i = 0; i < files.length; i++) {
        if (!props.max || allMedia.value.length < props.max) {
            if (files[i].size <= props.maxFilesize * 1000000) {
                const formData = new FormData();
                const url = URL.createObjectURL(files[i]);
                formData.set('image', files[i]);

                try {
                    const {data} = await axios.post(props.server, formData);
                    const addedImage = {
                        url: url,
                        remoteUrl: data.file.url,
                        name: data.name,
                        size: files[i].size,
                        type: files[i].type,
                    };
                    addedMedia.value.push(addedImage);

                    handleEmitChange(allMedia.value);
                    handleEmitAdd(addedImage, addedMedia.value);
                } catch (error) {
                    console.error(error);
                }
            } else {
                handleEmitMaxFileSize(files[i].size);
                if (props.warnings) {
                    alert('The file you are trying to upload is too big. \nMaximum Filesize: ' + props.maxFilesize + 'MB');
                }
                break;
            }
        } else {
            handleEmitMax(props.max);
            if (props.warnings) {
                alert('You have reached the maximum number of files that you can upload. \nMaximum Files: ' + props.max);
            }
            break;
        }
    }

    event.target.value = null;
    isLoading.value = false;
};
const removeAddedMedia = (index) => {
    const removedImage = addedMedia.value[index];
    addedMedia.value.splice(index, 1);
    handleEmitChange(allMedia.value);
    handleEmitRemove(removedImage, removedMedia.value);
};

const removeSavedMedia = (index) => {
    const removedImage = savedMedia.value[index];
    removedMedia.value.push(removedImage);
    savedMedia.value.splice(index, 1);
    handleEmitChange(allMedia.value);
    handleEmitRemove(removedImage, removedMedia.value);
};


</script>

<template>
    <div>
        <div
            class="mu-container"
            :class="isInvalid?'mu-red-border':''"
        >
            <Loader
                color="#0275d8"
                :active="isLoading"
                spinner="line-scale"
                background-color="rgba(255, 255, 255, .4)"
            />
            <div class="mu-elements-wraper">
                <!--UPLOAD BUTTON-->
                <div
                    v-if="allMedia.length < props.max"
                    class="mu-plusbox-container"
                >
                    <label
                        for="mu-file-input"
                        class="mu-plusbox"
                    >
                        <svg
                            class="mu-plus-icon"
                            xmlns="http://www.w3.org/2000/svg"
                            width="1em"
                            height="1em"
                            preserveAspectRatio="xMidYMid meet"
                            viewBox="0 0 24 24"
                        >
                            <g fill="none">
                                <path
                                    d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11s11-4.925 11-11S18.075 1 12 1zm1 15a1 1 0 1 1-2 0v-3H8a1 1 0 1 1 0-2h3V8a1 1 0 1 1 2 0v3h3a1 1 0 1 1 0 2h-3v3z"
                                    fill="currentColor"
                                />
                            </g>
                        </svg>
                    </label>
                    <input
                        id="mu-file-input"
                        type="file"
                        accept="image/*"
                        multiple
                        hidden
                        @change="fileChange"
                    >
                </div>
                <div
                    v-for="(image, index) in allMedia"
                    :key="index"
                    class="mu-image-container"
                >
                    <img
                        :src="image.url"
                        alt=""
                        class="mu-images-preview"
                    >
                    <button
                        class="mu-close-btn"
                        type="button"
                        @click="() => removeAddedMedia(index)"
                    >
                        <svg
                            class="mu-times-icon"
                            xmlns="http://www.w3.org/2000/svg"
                            width="0.65em"
                            height="0.65em"
                            preserveAspectRatio="xMidYMid meet"
                            viewBox="0 0 352 512"
                        >
                            <path
                                d="m242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28L75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256L9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"
                                fill="currentColor"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div>
            <div
                v-for="(image, index) in addedMedia"
                :key="index"
                class="mu-mt-1"
            >
                <input
                    type="text"
                    name="added_media[]"
                    :value="image.name"
                    hidden
                >
            </div>
            <div
                v-if="allMedia.length"
                class="mu-mt-1"
            >
                <input
                    type="text"
                    name="media"
                    value="1"
                    hidden
                >
            </div>
        </div>
    </div>
</template>


<style scoped>

.mu-container {
    background-color: #fbfbfb !important;
    border-radius: 5px !important;
    border-style: solid !important;
    border: 1px solid #9b9b9b !important;
    box-sizing: border-box !important;
    width: 100% !important;
    height: auto !important;
}

/* ----elements-wrapper--- */

.mu-elements-wraper {
    padding: 1rem !important;
    display: flex !important;
    flex-wrap: wrap !important;
}

/* ----plusbox--- */

.mu-plusbox-container {
    display: inline-flex !important;
    height: 90px !important;
    width: 140px !important;
    margin: 0.25rem !important;
}

.mu-plusbox {
    background-color: #ffffff !important;
    border: 1px dashed #818181 !important;
    border-radius: 5px !important;
    cursor: pointer !important;
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    width: 140px !important;
    height: 90px !important;
}

.mu-plusbox:hover {
    background-color: #f1f1f1 !important;
}

.mu-plusbox:hover > .mu-plus-icon {
    color: #028296 !important;
}

.mu-plus-icon {
    color: #00afca !important;
    font-size: 3rem !important;
    flex: 1;
}

/* ----media-preview---- */

.mu-image-container {
    width: 140px !important;
    height: 90px !important;
    margin: 0.25rem !important;

    position: relative;
}

.mu-images-preview {
    border-radius: 5px !important;
    border: 1px solid #818181 !important;
    width: 140px !important;
    height: 90px !important;
    transition: filter 0.1s linear;
    object-fit: cover;
    object-position: center;
}

.mu-images-preview:hover {
    filter: brightness(90%);
}

.mu-close-btn {
    background: none !important;
    color: white !important;
    border: none !important;
    padding: 0px !important;
    margin: 0px !important;
    cursor: pointer !important;

    position: absolute !important;
    top: 0px;
    right: 0px;
}

.mu-times-icon {
    font-size: 3rem !important;
    filter: drop-shadow(0px 0px 1px black);
}

.mu-close-btn:hover {
    color: red !important;
}

/* -------------------- */

.mu-red-border {
    border: 1px solid #dc3545 !important;
}

.mu-mt-1 {
    margin-top: 0.25rem !important;
}

/* -------------------- */

img {
    -webkit-user-drag: none;
    -khtml-user-drag: none;
    -moz-user-drag: none;
    -o-user-drag: none;
}

</style>
