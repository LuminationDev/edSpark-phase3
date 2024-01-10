<script setup>
import {onMounted, ref} from "vue";

import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import EducatorHero from "@/js/components/advice/EducatorHero.vue";
import PartnerHero from "@/js/components/advice/PartnerHero.vue";
import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";
import {adviceService} from "@/js/service/adviceService";

import AdviceHero from '../components/advice/AdviceHero.vue'
import CardLoading from '../components/card/CardLoading.vue';


const dagAdvice = ref([])
const partnerAdvice = ref([])
const generalAdvice = ref([])

onMounted(() => {
    dagAdvice.value = adviceService.fetchDagAdvice()
    partnerAdvice.value = adviceService.fetchPartnerAdvice()
    generalAdvice.value = adviceService.fetchGeneralAdvice()
})

</script>

<template>
    <AdviceHero />
    <div class="AdviceContentContainer DAGAdviceRow flex flex-col h-full">
        <CarouselGenerator
            :show-count="3"
            data-type="advice"
            :data-array="dagAdvice ? dagAdvice.slice(0,5) : []"
        />
    </div>


    <EducatorHero />
    <div
        class="EduAdviceCards grid grid-cols-1 gap-10 place-items-center mt-10 px-5 md:!grid-cols-2 lg:!grid-cols-3 lg:!px-huge"
    >
        <template v-if="generalAdvice && generalAdvice.length">
            <AdviceCard
                v-for="advice in generalAdvice.slice(0,6)"
                :key="advice.guid"
                :data="advice"
                :show-icon="true"
            />
        </template>
        <template v-else>
            <div
                class="col-span-1 md:!col-span-2 lg:!col-span-3"
            >
                <CardLoading
                    :number-of-rows="1"
                    :number-per-row="3"
                />
            </div>
        </template>
    </div>


    <PartnerHero />
    <CarouselGenerator
        :show-count="3"
        data-type="advice"
        :data-array="partnerAdvice ? partnerAdvice.slice(0,5) : []"
    />
</template>
