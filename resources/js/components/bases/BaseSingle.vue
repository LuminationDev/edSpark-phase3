-
<script setup>
import {useRoute, useRouter} from "vue-router";
import {onBeforeMount, ref, computed, watch, onUnmounted} from "vue";
import axios from 'axios'
import {isEqual} from "lodash";

import {imageURL, serverURL} from "@/js/constants/serverUrl";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import recommenderEdsparkSingletonFactory from "@/js/recommender/recommenderEdspark";

const props = defineProps({
    // to be advice, software, hardware etc
    contentType: {
        type: String,
        required: true
    }
})
const emits = defineEmits(['emitAvailableSubmenu','emitActiveTabToSpecificPage'])
const singleContent = ref({})
/**
 * type can be advice, software, hardware etc
 *  type singleContent = {
 *      post_id: number
 *      ${type}_type: string,
 *      author: string,
 *      post_title: string
 *      post_excerpt
 *      cover_image: string (link)
 *      extra_content: Object / Array
 *      post_status: string
 *      template: object/array
 *      post_date: string-date
 *      post_modified: string-date
 *      updated_at: string-date
 *  }
 */
const recommendedContent = ref({})
let byIdAPILink;
let recommendationAPILink;

switch (props.contentType) {
case 'software':
    byIdAPILink = 'fetchSoftwarePostById'
    break;
case 'advice':
    byIdAPILink = 'fetchAdvicePostById'
    break;
case 'hardware':
    byIdAPILink = 'fetchProductById'
    recommendationAPILink = 'fetchProductByBrand'
    break;
case 'event':
    byIdAPILink = 'fetchEventPostById'
    break;
case 'partner':
    byIdAPILink ='fetchPartnerById'
    break;

}


const route = useRoute()
const router = useRouter()
const currentId = computed(() => {
    if (route.params.id) {
        return route.params.id
    } else return 0
})

const getRecommendationBasedOnContentType = () => {
    switch (props.contentType) {
    case 'hardware':
        console.log('called recommendation for hardware')
        if (recommendationAPILink) {
            return axios.get(`${serverURL}/${recommendationAPILink}/${singleContent.value['brand']['brandName']}`).then(res => {
                recommendedContent.value = res.data
            }).catch(e =>{
                console.log(e.message)
            })
        }
        break;
    case 'software':
        console.log('called recommendation for software -- not complete TODO')
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
    if( singleContent.value.metadata && singleContent.value.metadata.filter(meta => Object.values(meta).includes('single_submenu'))){
        const availableSubMenuObject = singleContent.value.metadata.filter(meta => Object.values(meta).includes('single_submenu'))[0]
        if(availableSubMenuObject){
            const availableSubMenu = Object.values(availableSubMenuObject)[1] // bit rough but quite guaranteed to success
            emits('emitAvailableSubmenu', availableSubMenu)
        }
    }
    /// end of emiiting submenu

    getRecommendationBasedOnContentType()
})

const checkToReadOrFetchContent = async () =>{
    if (!window.history.state.content) { // doesn't exists
        if(!byIdAPILink) return
        console.log('No content passed in. Will request from server')
        await axios.get(`${serverURL}/${byIdAPILink}/${route.params.id}`).then(res => {
            singleContent.value = res.data
        })
    } else {
        //content exists in window.history.state
        if((JSON.parse(window.history.state.content).post_id || JSON.parse(window.history.state.content).id) == route.params.id){
            console.log('same id inside window history id compated to params id ')
            console.info('Advice content received from parent. No request will be sent to server')
            singleContent.value = JSON.parse(window.history.state.content)
        } else{
            await axios.get(`${serverURL}/${byIdAPILink}/${route.params.id}`).then(res => {
                singleContent.value = res.data
            })
        }
    }
}

watch(currentId, () => {
    if (window.history.state.content && singleContent.value) {
        if (!isEqual(JSON.parse(window.history.state.content), singleContent.value)) {
            singleContent.value = JSON.parse(window.history.state.content)
        }
    }
})
const handleEmitFromSubmenu = (value) => {
    emits('emitActiveTabToSpecificPage', value)
}

</script>
<template>
    <div class="singleContainer flex flex-col">
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
</template>

<style scoped>
h2 {
    font-weight: bolder;
}

h3 {
    font-weight: bold;
}
</style>
