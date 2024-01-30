<script setup>
import axios from 'axios'
import {computed, onBeforeMount, ref, watch} from "vue";
import {useRoute, useRouter} from "vue-router";

import GenericButton from "@/js/components/button/GenericButton.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import useErrorMessage from "@/js/composables/useErrorMessage";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {convertLinksToEmbeds, isObjectEmpty} from "@/js/helpers/objectHelpers";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {useAdviceStore} from "@/js/stores/useAdviceStore";
import {useHardwareStore} from "@/js/stores/useHardwareStore";
import {useSoftwareStore} from "@/js/stores/useSoftwareStore";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    // to be advice, software, hardware etc
    contentType: {
        type: String,
        required: true
    }
})
const emits = defineEmits(['emitAvailableSubmenu', 'emitActiveTabToSpecificPage'])
const {error, setError, clearError} = useErrorMessage()
const route = useRoute()
const router = useRouter()
const userStore = useUserStore()
const softwareStore = useSoftwareStore()
const hardwareStore = useHardwareStore()
const adviceStore = useAdviceStore()

const singleContent = ref({})
const baseIsLoading = ref(!(props.contentType.toLowerCase() === 'school'))
const recommendedContent = ref({})
let byIdAPILink;

const isPreviewModeComputed = computed(() => {
    return userStore.getIfUserIsModerator && route.query.source === 'filament'
})
// Only be true if the server return posts which status is not Published
const showPreviewLabel = computed(() => {
    console.log(singleContent.value)
    return (props.contentType !== 'school' && props.contentType !== 'partner') && !(singleContent.value?.status && singleContent.value?.status === "Published") && route.params.preview;
})

switch (props.contentType) {
case 'software':
    byIdAPILink = API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POST_BY_ID
    break;
case 'advice':
    byIdAPILink = API_ENDPOINTS.ADVICE.FETCH_ADVICE_POST_BY_ID
    break;
case 'hardware':
    byIdAPILink = API_ENDPOINTS.HARDWARE.FETCH_HARDWARE_BY_ID
    break;
case 'event':
    byIdAPILink = API_ENDPOINTS.EVENT.FETCH_EVENT_POST_BY_ID
    break;
case 'partner':
    byIdAPILink = API_ENDPOINTS.PARTNER.FETCH_PARTNER_BY_ID
    break;
}

// const isPreviewMode = computed(() => {
//     return route.query.preview && userStore.getIfUserIsModerator
// })

const currentId = computed(() => {
    if (route.params.id) {
        return route.params.id
    } else return 0
})
const getRecommendationBasedOnContentType = () => {
    switch (props.contentType) {
    case 'hardware':
        if (singleContent.value.brand?.brandName) {
            hardwareStore.loadProductsByBrand(currentId.value)
        }
        break;
    case 'software':
        softwareStore.loadRelatedSoftware(currentId.value)
        break;
    case 'advice':
        adviceStore.loadRelatedAdvice(currentId.value)
        break;
    default:
        break;
    }
}

onBeforeMount(async () => {
    if (props.contentType !== 'school') {
        await fetchContent()

    }

    // }
    // code to emit available submenus - to be used in all baseSingle pages. remove hardcoded
    if (singleContent.value?.metadata && singleContent.value.metadata.filter(meta => Object.values(meta).includes('single_submenu'))) {
        const availableSubMenuObject = singleContent.value.metadata.filter(meta => Object.values(meta).includes('single_submenu'))[0]
        if (availableSubMenuObject) {
            const availableSubMenu = Object.values(availableSubMenuObject)[1] // bit rough but quite guaranteed to success
            emits('emitAvailableSubmenu', availableSubMenu)
        }
    }

    // Single pages slug generator and display on the url
    if (singleContent.value && props.contentType !== 'school') {
        const currentQueries = route.query
        await router.replace({
            params: {slug: lowerSlugify(getObjectTitleValue(singleContent.value))},
            query: currentQueries
        });
    }
    getRecommendationBasedOnContentType()
})


const fetchContent = async () => {
    const requestData = {
        id: +route.params.id,
        preview: isPreviewModeComputed.value
    }
    await axios.post(byIdAPILink, requestData).then(res => {
        singleContent.value = res.data.data
        if (singleContent.value.content && typeof singleContent.value.content === 'string') {
            singleContent.value.content = convertLinksToEmbeds(singleContent.value.content)
        }
    }).catch(err => {
        console.log(err)
        if (err.response?.data?.message) {
            setError(err.code, err.response.data.message)
        } else {
            setError(err.code, err.message)
        }
    }).finally(() => {
        baseIsLoading.value = false

    })
}
const getObjectTitleValue = (data) => {
    let titleKey = Object.keys(data).filter(key => key.includes('title'))[0];
    if (!titleKey) {
        titleKey = Object.keys(data).filter(key => key.includes('name'))[0]
    }

    return data[titleKey];
}
watch(currentId, async () => {
    await fetchContent()
    getRecommendationBasedOnContentType()

})
const handleEmitFromSubmenu = (value) => {
    emits('emitActiveTabToSpecificPage', value)
}


</script>
<template>
    <div
        v-if="baseIsLoading"
        class="flex justify-center py-10"
    >
        <div class="font-semibold text-xl">
            <Loader
                :loader-color="'#0072DA'"
                :loader-message="'Data loading'"
            />
        </div>
    </div>
    <div
        v-else-if="error.status"
        class="flex justify-center py-10"
    >
        <div class="flex font-semibold text-center text-xl">
            {{ error.message ? error.message : 'Sorry an error has occured' }}
        </div>
    </div>
    <div
        v-else-if="!isObjectEmpty(singleContent) || props.contentType === 'school'"
        class="flex flex-col singleContainer"
    >
        <slot
            name="hero"
            :content-from-base="singleContent"
            :emit-from-submenu="handleEmitFromSubmenu"
        />
        <div
            v-if="showPreviewLabel && !baseIsLoading"
            class="grid grid-cols-4 moderationRow mt-10"
        >
            <div
                class="col-span-3 font-semibold mb-4 previewLabel text-center text-xl"
            >
                Preview content (Moderation)
                <div class="font-medium text-base text-center">
                    {{
                        singleContent['modified_at'] ? "Created on " + formatDateToDayTime(singleContent['modified_at']) : ''
                    }}
                    <span class="font-semibold"> {{
                        singleContent?.author?.author_name ? "by " + singleContent?.author?.author_name : ""
                    }} </span>
                </div>
                <div class="col-span-1 flex">
                    <GenericButton :callback="() => {}">
                        Back to moderation
                    </GenericButton>
                </div>
            </div>
        </div>
        <slot
            name="content"
            :content-from-base="singleContent"
            :recommendation-from-base="recommendedContent"
        />
    </div>
</template>

<style scoped>
h2 {
    font-weight: bolder;
}

h3 {
    font-weight: bold;
}

:deep(a) {
    text-decoration: underline;
}
</style>
