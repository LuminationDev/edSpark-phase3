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
import {lowerSlugify} from "@/js/helpers/slugifyHelper";
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
    return !(singleContent.value?.status && singleContent.value?.status === "Published");
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
    // if (!isPreviewMode.value && userStore.getIfUserIsModerator && route.query.source) {
    //     if (route.query.source === 'vue') {
    //         console.log('only works for school')
    //         // display pending school content.. maybe leave it to the SchoolSingle
    //     } else if (route.query.source === 'filament') {
    //         console.log('source moderation from filament')
    //         //fetch pendiong content here through api
    //     }
    //
    //     // perform fetch moderatid content through the API
    //     // TODO : FIX once finalised
    // } else {
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


// const checkToReadOrFetchContent = async () => {
//     if (!window.history.state.content) { // doesn't exists
//         if (!byIdAPILink) return // fetchByIdAPILink not exist terminate function
//         console.log('No content passed in. Will request from server')
//         // get post by id via API link
//         await axios.get(`${byIdAPILink}${route.params.id}`, useUserStore().getUserRequestParam).then(res => {
//             singleContent.value = res.data
//             singleContent.value.content = convertLinksToEmbeds(singleContent.value.content)
//             console.log('set new data haha yes')
//             baseIsLoading.value = false
//         }).catch(err => {
//             console.log(err)
//             baseIsLoading.value = false
//         })
//     } else {
//         // content exists in window.history.state. NO FETCH JUST PARSE from state
//         // then check if ID matches between the data inside state and current url
//         // if it matches, set the single content value. if not, go to else
//         // TODO: remove these comparison - make it simple
//         if ((JSON.parse(window.history.state.content).post_id || JSON.parse(window.history.state.content).id) === route.params.id) {
//             console.log('same id inside window history id compated to params id ')
//             console.info('Advice content received from parent. No request will be sent to server')
//             singleContent.value = JSON.parse(window.history.state.content)
//             singleContent.value.content = convertLinksToEmbeds(singleContent.value.content)
//             baseIsLoading.value = false
//
//         } else {
//             // state has content but ID different, send fetch
//             console.log('requesting content ')
//             await axios.get(`${byIdAPILink}${route.params.id}`).then(res => {
//                 singleContent.value = res.data
//                 if (singleContent.value.content && typeof singleContent.value.content === 'string') {
//                     singleContent.value.content = convertLinksToEmbeds(singleContent.value.content)
//                 }
//                 baseIsLoading.value = false
//
//             }).catch(err => {
//                 console.log(err)
//                 baseIsLoading.value = false
//             })
//         }
//     }
// }

const fetchContent = async () => {
    const requestData = {
        id: route.params.id,
        preview: isPreviewModeComputed.value
    }
    await axios.post(byIdAPILink, requestData).then(res => {
        singleContent.value = res.data.data
        if (singleContent.value.content && typeof singleContent.value.content === 'string') {
            singleContent.value.content = convertLinksToEmbeds(singleContent.value.content)
        }
    }).catch(err => {
        console.log(err)
        if (err.response?.data?.error) {
            // TODO: Fix when we implement response service from the backend
            setError(err.code, err.response.data.error)
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
            Sorry an error has occured <br>
            {{ error.message }}
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
        <div class="flex flex-row moderationRow mt-10">
            <div
                v-if="showPreviewLabel && !baseIsLoading"
                class="basis-4/5 font-semibold mb-4 previewLabel text-center text-xl"
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
            </div>
            <div class="basis-1/5 flex">
                <GenericButton :callback="() => {}">
                    Back to moderation
                </GenericButton>
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
