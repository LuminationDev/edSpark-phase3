<script setup>
import {useRoute} from "vue-router";
import {onBeforeMount, ref, computed, watch} from "vue";
import axios from 'axios'
import {isEqual} from "lodash";

import {imageURL, serverURL} from "@/js/constants/serverUrl";
import BaseHero from "@/js/components/bases/BaseHero.vue";

const props = defineProps({
    // to be advice, software, hardware etc
    contentType: {
        type: String,
        required: true
    }
})
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
const singleContent = ref({})
let apiLink;

switch (props.contentType){
case 'software':
    apiLink = 'fetchAdvicePostById'
    break;
case 'advice':
    apiLink ='fetchAdvicePostById'
    break;
}

const route = useRoute()
const currentId = computed(() =>{
    if(route.params.id){
        return route.params.id

    }
    else return 0
})

onBeforeMount(async () =>{
    // TODO: Need to compare if params and adviceSingleContent is the same
    if(!route.params.content){
        console.log('No adviceContent passed in. Will request from server')
        await axios.get(`${serverURL}/${apiLink}/${route.params.id}`).then(res => {
            singleContent.value = res.data
        })
    } else{
        console.info('Advice content received from parent. No request will be sent to server')
        singleContent.value = JSON.parse(route.params.content)
    }
})

watch(currentId ,() => {
    console.log('inside watcher params id')
    if(route.params.content && singleContent.value){
        if(!isEqual(JSON.parse(route.params.content), singleContent.value)){
            singleContent.value = JSON.parse(route.params.content)
        }
    }
} )


</script>
<template>
    <div class="singleContainer flex flex-col">
        <slot
            name="hero"
            :content-from-base="singleContent"
        />
        <slot
            name="content"
            :content-from-base="singleContent"
        />
    </div>
</template>