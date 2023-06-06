<script setup>
import BaseHero from '@/js/components/bases/BaseHero.vue';
import BaseSingle from '@/js/components/bases/BaseSingle.vue';
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";
import HardwareCarousel from '@/js/components/hardware/HardwareCarousel.vue';
import GenericCard from '../components/card/GenericCard.vue';
import HardwareExtraContentRenderer from "@/js/components/hardware/HardwareExtraContentRenderer.vue";

import {ref, onBeforeMount, onMounted, computed} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import {useHardwareStore} from '../stores/useHardwareStore.js';
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";
import HardwareCard from "@/js/components/hardware/HardwareCard.vue";
import HardwareLaptopTechSpecs from "@/js/components/hardware/HardwareLaptopTechSpecs.vue";
import HardwareEmergingTechSpecs from "@/js/components/hardware/HardwareEmergingTechSpecs.vue";
import HardwareAudioVisualTechSpecs from "@/js/components/hardware/HardwareAudioVisualTechSpecs.vue";

const hardwareStore = useHardwareStore();
const recommendedResources = ref([]);
const baseContentRef = ref(null);
const route = useRoute();
const router = useRouter();

const userStore = useUserStore()
const {currentUser } = storeToRefs(userStore)

const likeBookmarkData = {
    post_id: route.params.id,
    user_id: currentUser.value.id,
    post_type: 'hardware'
};


/**
 * Submenu specific codes
 */
const hardwareSubmenu = [
    {
        displayText: 'Overview',
        value: 'overview'
    },
    {
        displayText: 'Tech Specs',
        value: 'techspecs'
    }]
const activeSubmenu = ref(hardwareSubmenu[0]['value'])

// handleChangeSubmenu will be triggered by emit from BaseSingle
const handleChangeSubmenu = (value) => {
    activeSubmenu.value = value
    console.log('active submenu has been changed to ', value)
}
/**
 * End of submenu specific code  plus @emit-active-tab-to-specific-page in BaseSingle
 * */


</script>

<template>
    <BaseSingle
        content-hero="hardware"
        :content-type="'hardware'"
        @emit-active-tab-to-specific-page="handleChangeSubmenu"
    >
        <template #hero="{contentFromBase, emitFromSubmenu}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
            >
                <template #titleText>
                    {{ contentFromBase['product_name'] }}
                </template>
                <template
                    v-if="contentFromBase['brand']"
                    #hardwareProvider
                >
                    <div>
                        <p class="text-[15px] font-medium">
                            {{ contentFromBase['brand']['brandName'] }}
                        </p>
                    </div>
                </template>
                <template #subtitleText2>
                    <div v-html="contentFromBase['product_excerpt']" />
                </template>
                <template #submenu>
                    <div class="hardwareSubmenu flex flex-row gap-4 z-40 cursor-pointer">
                        <BaseSingleSubmenu
                            :emit-to-base="emitFromSubmenu"
                            :menu-array="hardwareSubmenu"
                            :active-subpage="activeSubmenu"
                        />
                    </div>
                </template>
            </BaseHero>
        </template>

        <template #content="{ contentFromBase,recommendationFromBase }">
            <div class="flex flex-row w-full mt-20">
                <template v-if="activeSubmenu === hardwareSubmenu[0]['value']">
                    <div
                        :id="contentFromBase['id']"
                        ref="baseContentRef"
                        class="py-4 px-20 flex flex-col w-full overflow-hidden"
                    >
                        <!-- Carousel here -->
                        <HardwareCarousel
                            :slide-items="contentFromBase"
                        />
                        <div class="flex flex-row mt-[64px] gap-12">
                            <div class="w-2/3 flex flex-col py-4">
                                <div>
                                    <h1
                                        class="text-2xl flex font-bold uppercase"
                                    >
                                        {{ contentFromBase['product_name'] }}
                                    </h1>
                                </div>
                                <div
                                    class="pt-8 text-lg flex flex-col content-paragraph overflow-hidden max-w-full"
                                    v-html="contentFromBase['product_content']"
                                />
                                <template
                                    v-for="(content,index) in contentFromBase['extra_content']"
                                    :key="index"
                                >
                                    <HardwareExtraContentRenderer :content="content" />
                                </template>
                            </div>

                            <div
                                v-if="contentFromBase['brand']"
                                class="w-1/3"
                            >
                                <div
                                    v-if="recommendationFromBase && recommendationFromBase.length > 0"
                                    class="bg-[#048246]/5 flex flex-col px-6 py-6 gap-6"
                                >
                                    <h3 class="text-[24px] font-bold mx-auto pb-8">
                                        More from {{ contentFromBase['brand']['brandName'] }}
                                    </h3>
                                    <div
                                        v-for="(item,index) in recommendationFromBase.slice(0,2)"
                                        :key="index"
                                        class="flex justify-between"
                                    >
                                        <HardwareCard
                                            class="mx-auto bg-white"
                                            :hardware-content="item"
                                            :number-per-row="1"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-if="activeSubmenu === hardwareSubmenu[1]['value']">
                    <div class="text-black py-4 px-20 w-full">
                        <template v-if="contentFromBase['category']['categoryName'] == 'Laptop'">
                            <HardwareLaptopTechSpecs :extra-content="contentFromBase['extra_content']" />
                        </template>
                        <template v-else-if="contentFromBase['category']['categoryName'] == 'Emerging Technology'">
                            <HardwareEmergingTechSpecs :extra-content="contentFromBase['extra_content']" />
                        </template>
                        <template v-else-if="contentFromBase['category']['categoryName'] == 'Audio Visual'">
                            <HardwareAudioVisualTechSpecs :extra-content="contentFromBase['extra_content']" />
                        </template>
                        <template v-else>
                            <div class="text-black w-full flex justify-center text-2xl">
                                Sorry technical specification is not available for this item
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </template>
    </BaseSingle>
</template>

<style lang="scss">
.content-paragraph {
    h2 {
        font-size: 24px;
        font-weight: 600;
        padding-top: 16px;
        padding-bottom: 12px;
    }

    h3 {
        font-weight: 500;
        font-size: 20px;
        padding-top: 8px;
        padding-bottom: 8px;
    }

    p {
        padding-bottom: 12px;
    }

    ul {
        padding-bottom: 12px;
        padding-left: 36px;
        list-style: disc;
    }
}
</style>
