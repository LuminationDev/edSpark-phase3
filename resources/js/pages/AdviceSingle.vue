`<script setup>
import {useRoute} from "vue-router";
import {onBeforeMount, ref, computed, watch} from "vue";
import axios from 'axios'
import {isEqual} from "lodash";

import {imageURL, serverURL} from "@/js/constants/serverUrl";
import BaseHero from "@/js/components/hero/BaseHero.vue";
import AdviceSingleExtraContentRenderer from "@/js/components/advice/AdviceSingleExtraContentRenderer.vue";
import AdviceSingleCuratedContent from "@/js/components/advice/AdviceSingleCuratedContent.vue";


const route = useRoute()
const currentId = computed(() =>{
    if(route.params.id){
        return route.params.id

    }
    else return 0
})
/**
 *  type AdviceSingleContent = {
 *      post_id: number
 *      advice_type: string,
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
const adviceSingleContent = ref({})

onBeforeMount(async () =>{
    // TODO: Need to compare if params and adviceSingleContent is the same
    if(!route.params.adviceContent){
        console.log('No adviceContent passed in. Will request from server')
        await axios.get(`${serverURL}/fetchAdvicePostById/${route.params.id}`).then(res => {
            adviceSingleContent.value = res.data
        })
    } else{
        console.info('Advice content received from parent. No request will be sent to server')
        adviceSingleContent.value = JSON.parse(route.params.adviceContent)
    }
})

watch(currentId ,() => {
    console.log('inside watcher params id')
    if(route.params.adviceContent && adviceSingleContent.value){
        if(!isEqual(JSON.parse(route.params.adviceContent), adviceSingleContent.value)){
            adviceSingleContent.value = JSON.parse(route.params.adviceContent)
        }
    }
} )

const temp_cover_image = 'uploads\\/school\\/edspark-school-5b586cc3d79f3dc9c34a05a44e2e83e3.jpg'

</script>
<template>
    <div class="adviceSingleContainer flex flex-col">
        <BaseHero
            :background-url="temp_cover_image"
        >
            <template #titleText>
                {{ adviceSingleContent['post_title'] }}
            </template>
            <template #subtitleText1>
                {{ adviceSingleContent['post_date'] }}
            </template>
            <template #subtitleText2>
                {{ adviceSingleContent['post_excerpt'] }}
            </template>
        </BaseHero>
        <!--    All content underneath AdviceHero    -->
        <div
            class="adviceSingleContent p-4 px-8 flex flex-row w-full overflow-hidden"
        >
            <!--    Content of the Advice    -->
            <div class="w-2/3 flex flex-col flex-wrap border-2">
                <div class="text-2xl flex font-bold uppercase">
                    Getting started
                </div>
                <div
                    class="text-lg flex content-paragraph overflow-hidden max-w-full"
                    v-html="adviceSingleContent['post_content']"
                />
                <div class="text-lg">
                    Some other content need to wait for data from backend
                </div>
                <div
                    v-for="(content,index) in adviceSingleContent['extra_content']"
                    :key="index"
                    class="extraContent"
                >
                    <AdviceSingleExtraContentRenderer :content="content" />
                </div>
            </div>
            <!--      Curated Content      -->
            <div class="w-1/3 flex flex-col">
                <AdviceSingleCuratedContent />
            </div>
        </div>
    </div>
</template>