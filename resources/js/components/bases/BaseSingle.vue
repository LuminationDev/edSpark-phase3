-
<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import lowerSlugify from "@/js/helpers/slugifyHelper";
import {useHardwareStore} from "@/js/stores/useHardwareStore";
import {useSoftwareStore} from "@/js/stores/useSoftwareStore";
import {useUserStore} from "@/js/stores/useUserStore";
import {useRoute, useRouter} from "vue-router";
import {onBeforeMount, ref, computed, watch, onUnmounted} from "vue";
import axios from 'axios'
import {isEqual} from "lodash";

import {serverURL} from "@/js/constants/serverUrl";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import recommenderEdsparkSingletonFactory from "@/js/recommender/recommenderEdspark";
import {isObjectEmpty} from "@/js/helpers/objectHelpers";
import Loader from "@/js/components/spinner/Loader.vue";

const props = defineProps({
    // to be advice, software, hardware etc
    contentType: {
        type: String,
        required: true
    }
})
const emits = defineEmits(['emitAvailableSubmenu', 'emitActiveTabToSpecificPage'])
const singleContent = ref({})
const baseIsLoading = ref(!(props.contentType.toLowerCase() === 'school'))

const recommendedContent = ref({})
let byIdAPILink;
let recommendationAPILink;

switch (props.contentType) {
case 'software':
    byIdAPILink = API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POST_BY_ID
    break;
case 'advice':
    byIdAPILink = API_ENDPOINTS.ADVICE.FETCH_ADVICE_POST_BY_ID
    break;
case 'hardware':
    byIdAPILink = API_ENDPOINTS.HARDWARE.FETCH_HARDWARE_BY_ID
    recommendationAPILink = API_ENDPOINTS.HARDWARE.FETCH_HARDWARE_BY_BRAND
    break;
case 'event':
    byIdAPILink = API_ENDPOINTS.EVENT.FETCH_EVENT_POST_BY_ID
    break;
case 'partner':
    byIdAPILink = API_ENDPOINTS.PARTNER.FETCH_PARTNER_BY_ID
    break;

}


const route = useRoute()
const router = useRouter()
const currentId = computed(() => {
    if (route.params.id) {
        return route.params.id
    } else return 0
})

const softwareStore = useSoftwareStore()
const hardwareStore = useHardwareStore()

const getRecommendationBasedOnContentType = () => {
    switch (props.contentType) {
    case 'hardware':
        if (singleContent.value.brand?.brandName){
            hardwareStore.loadProductsByBrand(singleContent.value.brand?.brandName)
        }
        break;
    case 'software':
        softwareStore.loadRecommendedSoftware(currentId.value)
        break;
    case 'advice':
        console.log('called recommendation for advice -- not complete TODO')
        break;
    default:
        console.log('no recommendation request was sent')
    }
}

onBeforeMount(async () => {
    /**
     * Get content from history state or fetch from recommender
     */
    await checkToReadOrFetchContent()

    // code to emit available submenus - to be used in all baseSingle pages. remove hardcoded
    if (singleContent.value.metadata && singleContent.value.metadata.filter(meta => Object.values(meta).includes('single_submenu'))) {
        const availableSubMenuObject = singleContent.value.metadata.filter(meta => Object.values(meta).includes('single_submenu'))[0]
        if (availableSubMenuObject) {
            const availableSubMenu = Object.values(availableSubMenuObject)[1] // bit rough but quite guaranteed to success
            emits('emitAvailableSubmenu', availableSubMenu)
        }
    } else {

    }
    /// end of emiiting submenu

    if (singleContent.value) {
        console.log(lowerSlugify(getObjectTitleValue(singleContent.value)))
        await router.replace({params: {slug: lowerSlugify(getObjectTitleValue(singleContent.value))}});
    }

    getRecommendationBasedOnContentType()
})

const checkToReadOrFetchContent = async () => {
    if (!window.history.state.content) { // doesn't exists
        if (!byIdAPILink) return
        console.log('No content passed in. Will request from server')
        await axios.get(`${byIdAPILink}${route.params.id}`, useUserStore().getUserRequestParam).then(res => {
            singleContent.value = res.data
            console.log('set new data haha yes')
            baseIsLoading.value = false
        }).catch(err => {
            console.log(err)
            baseIsLoading.value = false
        })
    } else {
        //content exists in window.history.state
        if ((JSON.parse(window.history.state.content).post_id || JSON.parse(window.history.state.content).id) == route.params.id) {
            console.log('same id inside window history id compated to params id ')
            console.info('Advice content received from parent. No request will be sent to server')
            singleContent.value = JSON.parse(window.history.state.content)
            baseIsLoading.value = false

        } else {
            await axios.get(`${byIdAPILink}${route.params.id}`).then(res => {
                singleContent.value = res.data
                baseIsLoading.value = false

            }).catch(err => {
                console.log(err)
                baseIsLoading.value = false
            })
        }
    }
}
const getObjectTitleValue = (data) => {
    let titleKey = Object.keys(data).filter(key => key.includes('title'))[0];
    if (!titleKey) {
        titleKey = Object.keys(data).filter(key => key.includes('name'))[0]
    }

    return data[titleKey];
}
watch(currentId, () => {
    if (window.history.state.content && singleContent.value) {
        if (!isEqual(JSON.parse(window.history.state.content), singleContent.value)) {
            singleContent.value = JSON.parse(window.history.state.content)
            baseIsLoading.value = false
        }
    } else {
        checkToReadOrFetchContent()
    }
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
                :loader-message="'Data Loading'"
            />
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
        <slot
            name="content"
            :content-from-base="singleContent"
            :recommendation-from-base="recommendedContent"
        />
    </div>
    <div
        v-else
        class="flex justify-center py-10"
    >
        <div class="font-semibold text-xl">
            Sorry content not available.
            Please go back
        </div>
    </div>
</template>

<style scoped>
h2 {
    font-weight: bolder;
}

h3 {
    font-weight: bold;
}
</style>
