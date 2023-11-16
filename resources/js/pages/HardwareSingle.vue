<script setup lang="ts">
import purify from "dompurify";
import {ref} from 'vue';

import BaseBreadcrumb from "@/js/components/bases/BaseBreadcrumb.vue";
import BaseHero from '@/js/components/bases/BaseHero.vue';
import BaseSingle from '@/js/components/bases/BaseSingle.vue';
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";
import HardwareAudioVisualTechSpecs from "@/js/components/hardware/HardwareAudioVisualTechSpecs.vue";
import HardwareCarousel from '@/js/components/hardware/HardwareCarousel.vue';
import HardwareEmergingTechSpecs from "@/js/components/hardware/HardwareEmergingTechSpecs.vue";
import HardwareLaptopTechSpecs from "@/js/components/hardware/HardwareLaptopTechSpecs.vue";
import HardwareSingleBrandContent from "@/js/components/hardware/HardwareSingleBrandContent.vue";
import ExtraResourceTemplateDisplay from "@/js/components/renderer/ExtraResourceTemplateDisplay.vue";
import {edSparkContentSanitizer} from "@/js/helpers/objectHelpers";

const baseContentRef = ref(null);
/**
 * Submenu specific codes
 */

type SubmenuObjectType = {
    displayText: string,
    value: string
}
const hardwareSubmenu: Array<SubmenuObjectType> = [
    {
        displayText: 'Overview',
        value: 'overview'
    },
    {
        displayText: 'Tech specs',
        value: 'techspecs'
    }]
const activeSubmenu = ref(hardwareSubmenu[0]['value'])

// handleChangeSubmenu will be triggered by emit from BaseSingle
const handleChangeSubmenu = (value): void => {
    activeSubmenu.value = value
    console.log('active submenu has been changed to ', value)
}
/**
 * End of submenu specific code  plus @emit-active-tab-to-specific-page in BaseSingle
 * */

const colorTheme = ref('hardwareGreen')
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
                :swoosh-color-theme="colorTheme"
            >
                <template #breadcrumb>
                    <BaseBreadcrumb
                        :child-page="contentFromBase.title"
                        parent-page="hardware"
                        :color-theme="colorTheme"
                    />
                </template>
                <template #titleText>
                    {{ contentFromBase['title'] }}
                </template>
                <template
                    v-if="contentFromBase['brand']"
                    #hardwareProvider
                >
                    <div>
                        <p class="font-medium text-[15px]">
                            {{ contentFromBase['brand']['brandName'] }}
                        </p>
                    </div>
                </template>
                <template #subtitleText2>
                    <div v-html="contentFromBase['excerpt']" />
                </template>
                <template #submenu>
                    <div class="cursor-pointer flex flex-row gap-4 hardwareSubmenu mb-[-1px] z-40">
                        <BaseSingleSubmenu
                            :emit-to-base="emitFromSubmenu"
                            :menu-array="hardwareSubmenu"
                            :active-subpage="activeSubmenu"
                        />
                    </div>
                </template>
            </BaseHero>
        </template>

        <template #content="{ contentFromBase }">
            <div class="flex flex-row mt-20 w-full">
                <template v-if="activeSubmenu === hardwareSubmenu[0]['value']">
                    <div
                        :id="contentFromBase['id']"
                        ref="baseContentRef"
                        class="flex flex-col overflow-hidden px-5 py-4 w-full lg:!px-20"
                    >
                        <HardwareCarousel
                            :slide-items="contentFromBase"
                        />
                        <div class="flex flex-col gap-6 mt-[64px] lg:!flex-row">
                            <div class="flex flex-col py-4 w-full lg:!w-2/3">
                                <div>
                                    <h1
                                        class="flex font-bold text-2xl"
                                    >
                                        {{ contentFromBase['name'] }}
                                    </h1>
                                </div>
                                <div
                                    class="
                                        flex
                                        content-paragraph
                                        flex-col
                                        max-w-full
                                        overflow-hidden
                                        pt-8
                                        text-lg
                                        "
                                    v-html="edSparkContentSanitizer(contentFromBase['content'])"
                                />
                                <div
                                    v-if="contentFromBase['extra_content'] && contentFromBase['extra_content'].length"
                                    class="extraResourcesContainer"
                                >
                                    <ExtraResourceTemplateDisplay :content="contentFromBase['extra_content']" />
                                </div>
                            </div>

                            <div
                                v-if="contentFromBase['brand']"
                                class="w-full lg:!w-1/3"
                            >
                                <HardwareSingleBrandContent />
                            </div>
                        </div>
                    </div>
                </template>
                <template v-if="activeSubmenu === hardwareSubmenu[1]['value']">
                    <div class="px-5 py-4 text-black w-full lg:!px-20">
                        <template v-if="contentFromBase['category']['categoryName'] === 'Laptop'">
                            <HardwareLaptopTechSpecs :extra-content="contentFromBase['extra_content']" />
                        </template>
                        <template v-else-if="contentFromBase['category']['categoryName'] === 'Emerging Technology'">
                            <HardwareEmergingTechSpecs :extra-content="contentFromBase['extra_content']" />
                        </template>
                        <template v-else-if="contentFromBase['category']['categoryName'] === 'Audio Visual'">
                            <HardwareAudioVisualTechSpecs :extra-content="contentFromBase['extra_content']" />
                        </template>
                        <template v-else>
                            <div class="flex justify-center text-2xl text-black w-full">
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
