<script setup>
import useSWRV from "swrv";
import {useRouter} from "vue-router";

import CardLoading from "@/js/components/card/CardLoading.vue";
import SectionHeader from "@/js/components/global/SectionHeader.vue";
import EventSectionPartner from "@/js/components/partners/EventSectionPartner.vue";
import PartnerCard from "@/js/components/partners/PartnerCard.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosFetcher} from "@/js/helpers/fetcher";

import PartnersHero from '../components/partners/PartnersHero.vue';


const router = useRouter()


const {
    data: partnerList,
    error: partnerListError,
    isValidating: partnerListIsValidating
} = useSWRV(API_ENDPOINTS.PARTNER.FETCH_ALL_PARTNERS, axiosFetcher, swrvOptions)

</script>

<template>
    <div>
        <PartnersHero />
        <SectionHeader
            title="Our Partners"
            :classes="'bg-secondary-blueberry'"
            section="laptops"
            button-text="View all partners"
            :button-callback="() => router.push('/browse/partner')"
        />
        <div
            class="PartnerListGalleryContainer
                grid
                grid-cols-1
                gap-10
                place-items-center
                px-5
                
                
                md:!grid-cols-2
                lg:!px-huge
                xl:!grid-cols-3"
        >
            <template v-if="partnerList">
                <template
                    v-for="(singlePartnerData,index) in partnerList
                    "
                    :key="index"
                >
                    <PartnerCard
                        :data="singlePartnerData"
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
        <SectionHeader
            :classes="'bg-secondary-cherry'"
            :section="'events'"
            :title="'Event Calendar'"
            :button-text="'View all events'"
            :button-callback="() => router.push('/browse/event')"
        />
        <EventSectionPartner />
    </div>
</template>
