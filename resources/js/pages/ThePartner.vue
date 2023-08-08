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
import CardLoading from "@/js/components/card/CardLoading.vue";


let recommender = recommenderEdsparkSingletonFactory().getInstance()
const router = useRouter()
const techList = ref({})


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
            button-text="View all partners"
            :button-callback="() => router.push('/browse/partner')"
        />
        <div
            class="PartnerListGalleryContainer grid grid-cols-1 gap-4 place-items-center px-5 md:!grid-cols-2 lg:!px-huge xl:!grid-cols-3"
        >
            <template v-if="partnerList?.partners">
                <template
                    v-for="(singlePartnerData,index) in partnerList.partners"
                    :key="index"
                >
                    <PartnerCard
                        :partner-content="singlePartnerData"
                    />
                </template>
            </template>
            <template v-else>
                <div class="col-span-1 md:!col-span-2 xl:!col-span-3">
                    <CardLoading
                        :number-of-rows="1"
                        :number-per-row="3"
                    />
                </div>
            </template>
        </div>
    </div>
</template>
