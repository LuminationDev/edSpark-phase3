<script setup>
import {onBeforeMount, ref, computed, onMounted} from "vue";
import {serverURL} from "@/js/constants/serverUrl";
import AdviceCard from "@/js/components/advice/AdviceCard.vue";

console.log('hehe')

const allCuratedAdvice = ref([])


onMounted( async () =>{
    console.log('inside before mount')
    await axios.get( `${serverURL}/fetchAdvicePosts`).then(res => {
        allCuratedAdvice.value = res.data
    })

})
//TODO - Look at some params. Random two for now
const twoRecommendation  = computed( () => {
    let temp = []
    if(allCuratedAdvice.value){
        for(let i= 0; i < 2 ; i++){
            console.log(allCuratedAdvice.value[Math.floor(Math.random() * allCuratedAdvice.value.length)])
            temp.push(allCuratedAdvice.value[Math.floor(Math.random() * allCuratedAdvice.value.length)])
        }
    }
    return temp
})

</script>
<template>
    <div
        v-if="allCuratedAdvice.length > 0"
        class="adviceSingleCuratedContentContainer flex flex-col justify-center items-center ml-4 px-10 rounded bg-orange-50"
    >
        <div class="curatedResourcesTitle uppercase font-bold text-2xl text-center py-8 my-2">
            Other Curated Resources
        </div>
        <AdviceCard
            v-for="(advice,index) in twoRecommendation"
            :key="index"
            :advice-content="advice"
            :show-icon="true"
            :number-per-row="1"
        />
    </div>
    <div v-else>
        Loading
    </div>
</template>