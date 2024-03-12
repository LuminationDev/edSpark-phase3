<script setup>
import { storeToRefs } from "pinia";
import { computed, reactive, ref } from 'vue'
import { useRoute } from "vue-router";

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
import { imageURL } from "@/js/constants/serverUrl";
import { partnerService } from "@/js/service/partnerService";
import { useUserStore } from "@/js/stores/useUserStore";


const { currentUser } = storeToRefs(useUserStore())
const availableSubmenu = ref(['overview', 'access'])
const route = useRoute()

const formattedSubmenuData = computed(() => {
    if (!availableSubmenu.value || availableSubmenu.value.length === 0) {
        return []
    } else {
        return availableSubmenu.value.map(menu => {

            if (menu === 'curriculum') {
                return {
                    displayText: 'Inspiration',
                    value: menu
                }
            }

            return {
                displayText: menu.charAt(0).toUpperCase() + menu.slice(1),
                value: menu
            }
        })

    }
})

const partnerData = reactive({
    overview: {},
    access: {},
    software: {},
    hardware: {},
    curriculum: {}
})
const fetchSubmenuData = async () => {
    // perform fetches here and assign value/data into parterData(reactive)
    console.log("Item: " + availableSubmenu.value);
    const partnerId = route.params.id
    for (const submenu of availableSubmenu.value) {
        switch (submenu) {
        case 'overview':
            partnerData[submenu] = { data: 'this is temporary string for partner overview' }
            break;
        case 'software':
            partnerData[submenu] = partnerService.fetchPartnerSoftware(partnerId).then(res => res)
            break;
        case 'hardware':
            partnerData[submenu] = partnerService.fetchPartnerHardware(partnerId).then(res => res)
            break;
        case 'curriculum':
            partnerData[submenu] = partnerService.fetchPartnerAdvice(partnerId).then(res => res)
            break;
        case 'access':
            partnerData[submenu] = { overview: 'access here' }
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

const colorTheme = ref('partnerBlue')
</script>

<template>
    <BaseSingle
        content-type="partner"
        @emit-available-submenu="handleEmittedAvailableSubmenu"
    >
        <template #hero="{ contentFromBase }">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
                :swoosh-color-theme="colorTheme"
            >
                <template #breadcrumb>
                    <BaseBreadcrumb
                        :child-page="contentFromBase.name"
                        parent-page="Partners"
                        :parent-page-link="'browse/partner'"
                        :color-theme="colorTheme"
                    />
                </template>

                <template #titleText>
                    <div class="">
                        <img
                            :src="`${imageURL}/${contentFromBase['logo']}`"
                            alt="logo"
                            class="h-16 rounded"
                        >
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
                        class="mb-[-1px]"
                    />
                </template>
            </BaseHero>
        </template>
        <template #content="{ contentFromBase, recommendationFromBase }">
            <div class="partnerSingleContentContainer px-5 lg:!px-10">
                <Suspense timeout="0"> 
                    <component
                        :is="partnerSubPageComponent"
                        :data="dynamicProps"
                        :content-from-base="contentFromBase"
                        :recommendation-from-base="recommendationFromBase"
                    />
                    <template #fallback>
                        <div class="flex justify-center items-center font-thin">
                            <Loader
                                :loader-color="'#0072DA'"
                                :loader-message="'Data loading'"
                            />
                        </div>
                    </template>
                </Suspense>
            </div>
        </template>
    </BaseSingle>
</template>
