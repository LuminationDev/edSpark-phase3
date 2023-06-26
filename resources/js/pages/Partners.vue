<script setup>
import PartnersHero from '../components/partners/PartnersHero.vue';
import recommenderEdsparkSingletonFactory from "@/js/recommender/recommenderEdspark";
import {onMounted, ref} from "vue";
import useSWRV from "swrv";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {SWRVKeys} from "@/js/constants/swrvKeys";
import SectionHeader from "@/js/components/global/SectionHeader.vue";
import {useRouter} from "vue-router";
import PartnerCard from "@/js/components/partners/PartnerCard.vue";
const router = useRouter()
// const recommender = recommenderEdsparkSingletonFactory.getInstance(currentUser.value.id,currentUser.value.role, currentUser.value.site_id)
const recommender = recommenderEdsparkSingletonFactory().getInstance(64,'Partner', 100)
const techList = ref({})
console.log('hello inside partner')
recommender._logRecommenderData()

onMounted(() =>{
    // recommender.getTechByPartnerAsync().then(res =>{
    //     techList.value = res
    // })
})


const {
    data: partnerData,
    error: partnerError,
    isValidating: partnerIsValidating
} = useSWRV(SWRVKeys.PARTNER_PERSONALISED_TECH,() => recommender.getTechByPartnerAsync(), swrvOptions)


const {
    data: partnerList,
    error: partnerListError,
    isValidating: partnerListIsValidating
} = useSWRV(SWRVKeys.PARTNER_ALL_PARTNERS,() => recommender.getAllPartnersForPartner(), swrvOptions)

</script>

<template>
    <div>
        <PartnersHero />
        <SectionHeader
            title="Our Partners"
            :classes="'bg-secondary-darkBlue'"
            section="laptops"
            button-text="View all Partners"
            :button-callback="() => router.push('/browse/partners')"
        />
        <!--        {{ partnerError }}-->
        <!--        <pre> {{ partnerData }}</pre>-->
        <div
            v-if="partnerList?.partners"
            class="PartnerListGalleryContainer flex flex-row"
        >
            <template
                v-for="(singlePartnerData,index) in partnerList.partners"
                :key="index"
            >
                <PartnerCard
                    :partner-content="singlePartnerData"
                />
            </template>
        </div>
    </div>
</template>
