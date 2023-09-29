<script setup>
import {storeToRefs} from "pinia";
import {computed,  reactive,ref} from 'vue'
import {useRoute} from "vue-router";

import BaseBreadcrumb from "@/js/components/bases/BaseBreadcrumb.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";
import PartnerAccess from "@/js/components/partners/partnerSubPages/PartnerAccess.vue";
import PartnerCurriculum from "@/js/components/partners/partnerSubPages/PartnerCurriculum.vue";
import PartnerHardware from "@/js/components/partners/partnerSubPages/PartnerHardware.vue";
import PartnerOverview from "@/js/components/partners/partnerSubPages/PartnerOverview.vue";
import PartnerSoftware from "@/js/components/partners/partnerSubPages/PartnerSoftware.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {imageURL} from "@/js/constants/serverUrl";
import recommenderEdsparkSingletonFactory from "@/js/recommender/recommenderEdspark";
import {partnerService} from "@/js/service/partnerService";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({})
const {currentUser} = storeToRefs(useUserStore())

const emits = defineEmits([])
const availableSubmenu = ref(['overview', 'access'])

const formattedSubmenuData = computed(() => {
    if (!availableSubmenu.value || availableSubmenu.value.length === 0) {
        return []
    } else {
        return availableSubmenu.value.map(menu => {
            return {
                displayText: menu.charAt(0).toUpperCase() + menu.slice(1),
                value: menu
            }
        })

    }
})
//TODO: Replace site_id
const recommender = recommenderEdsparkSingletonFactory().getInstance(currentUser.value.id, currentUser.value.role, currentUser.value.site_id || 123)

const partnerData = reactive({
    overview: {},
    access: {},
    software: {},
    hardware: {},
    curriculum: {}
})
const route = useRoute()


const fetchSubmenuData = async() => {
    // perform fetches here and assign value/data into parterData(reactive)
    const partnerId  = route.params.id
    for (const submenu of availableSubmenu.value) {
        switch (submenu) {
        case 'overview':
            partnerData[submenu] = {data: 'this is temporary string for partner overview'}
            break;
        case 'software':
            partnerData[submenu] = partnerService.fetchPartnerResource('software', partnerId)
            break;
        case 'hardware':
            partnerData[submenu] = partnerService.fetchPartnerResource('hardware', partnerId)
            break;
        case 'curriculum':
            partnerData[submenu] = partnerService.fetchPartnerResource('advice', partnerId)
            break;
        case 'access':
            partnerData[submenu] = {overview: 'access here'}
            break;

        }


    }
}

const activeSubMenu = ref(availableSubmenu.value[0] || '')
const handleChangeSubmenu = (value) => {
    activeSubMenu.value = value
}

const handleEmittedAvailableSubmenu = (value) => {
    if (value) {
        availableSubmenu.value = value.split(',')
        console.log('SubMenu Activated')
    } else {
        console.log('No Submenu was passed -- Do here to set the automatic /default ')
    }
    activeSubMenu.value = availableSubmenu.value[0]
    fetchSubmenuData()
}

// computed value returning dynamic component
const partnerSubPageComponent = computed(() => {
    switch (activeSubMenu.value) {
    case 'overview':
        return PartnerOverview
    case 'access':
        return PartnerAccess
    case 'hardware':
        return PartnerHardware
    case 'software':
        return PartnerSoftware
    case 'curriculum':
        return PartnerCurriculum
    default:
        return PartnerOverview
    }
})

// allocate data from reactive state into computed value for dynamic component
// work as a pair with partnerSubPageComponent computed value
const dynamicProps = computed(() => {
    switch (activeSubMenu.value) {
    case 'overview':
        return partnerData.overview
    case 'access':
        return partnerData.access
    case 'hardware':
        return partnerData.hardware
    case 'software':
        return partnerData.software
    case 'curriculum':
        return partnerData.curriculum
    default:
        return partnerData.overview
    }
})

const colorTheme = ref('blue')
</script>

<template>
    <BaseSingle
        content-type="partner"
        @emit-available-submenu="handleEmittedAvailableSubmenu"
    >
        <template #hero="{contentFromBase}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
                :swoosh-color-theme="colorTheme"
            >
                <template #breadcrumb>
                    <BaseBreadcrumb
                        :child-page="contentFromBase.name"
                        parent-page="partners"
                        :color-theme="colorTheme"
                    />
                </template>

                <template #titleText>
                    <div class="flex items-center flex-row">
                        <div class="flex justify-center items-center h-20 mx-4 smallPartnerLogo w-24">
                            <img
                                :src="`${imageURL}/${contentFromBase['logo']}`"
                                alt="logo"
                            >
                        </div>
                        <span>
                            {{ contentFromBase['name'] }}

                        </span>
                    </div>
                </template>
                <template #authorName>
                    {{ contentFromBase['name'] }}
                </template>
                <template #subtitleText1>
                    {{ contentFromBase['motto'] }}
                </template>
                <template #subtitleText2>
                    {{ contentFromBase['introduction'] }}
                </template>
                <template #submenu>
                    <BaseSingleSubmenu
                        :active-subpage="activeSubMenu"
                        :emit-to-base="handleChangeSubmenu"
                        :menu-array="formattedSubmenuData"
                    />
                </template>
            </BaseHero>
        </template>
        <template #content="{contentFromBase,recommendationFromBase}">
            <div class="mt-20 partnerSingleContentContainer px-5 lg:!px-10">
                <Suspense timeout="0">
                    <component
                        :is="partnerSubPageComponent"
                        :data="dynamicProps"
                        :content-from-base="contentFromBase"
                        :recommendation-from-base="recommendationFromBase"
                    />
                    <template #fallback>
                        <div class="flex justify-center items-center">
                            <Loader
                                :loader-color="'#0072DA'"
                                :loader-message="'Data Loading'"
                            />
                        </div>
                    </template>
                </Suspense>
            </div>
        </template>
    </BaseSingle>
</template>
