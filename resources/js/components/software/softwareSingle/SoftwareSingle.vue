<script setup>
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import SoftwareSingleCuratedContent from "@/js/components/software/softwareSingle/SoftwareSingleCuratedContent.vue";
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";
import {ref} from 'vue'
/**
 *  type softwareSingleContent = {
 *      post_id: number
 *      software_type: string,
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


//submenu format
/**
 * Submenu format
 * {
 *     displayText: 'details'
 *     value: 'detail'
 * },
 * {
 *
 *     displayText: 'How to access'
 *     value: 'access'
 * }
 * const activetab =
 */

/**
 * Submenu specific codes
 */
const softwareSubmenu = [
    {
        displayText: 'Details',
        value: 'detail'
    },
    {

        displayText: 'How to access',
        value: 'access'
    }]
const activeSubmenu = ref(softwareSubmenu[0]['value'])

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
        content-type="software"
        @emit-active-tab-to-specific-page="handleChangeSubmenu"
    >
        <template #hero="{contentFromBase, emitFromSubmenu}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
            >
                <template #titleText>
                    {{ contentFromBase['post_title'] }}
                </template>
                <template #subtitleText1>
                    <div> {{ contentFromBase['author'] }}</div>
                    <div> {{ contentFromBase['post_date'] }}</div>
                </template>
                <template #subtitleText2>
                    <div v-html="contentFromBase['post_excerpt']" />
                </template>
                <!--  Selectable sub menu    -->
                <template #submenu>
                    <div class="softwareSubmenu flex flex-row gap-4 z-40 cursor-pointer">
                        <BaseSingleSubmenu
                            :emit-to-base="emitFromSubmenu"
                            :menu-array="softwareSubmenu"
                            :active-subpage="activeSubmenu"
                        />
                    </div>
                </template>
            </BaseHero>
        </template>
        <template #content="{contentFromBase}">
            <div
                class="softwareSingleContent p-10 pl-12 pt-0 mt-10 flex flex-row w-full overflow-hidden"
            >
                <template
                    v-if="activeSubmenu == 'detail'"
                >
                    <div class="w-2/3 flex flex-col flex-wrap py-4 px-2">
                        <div class="text-2xl flex font-bold uppercase">
                            Getting started
                        </div>
                        <div
                            class="text-lg flex flex-col content-paragraph overflow-hidden max-w-full"
                            v-html="contentFromBase['post_content']"
                        />
                        <div class="extraResourcesContainer">
                            <div class="text-2xl flex extraContentHeading font-bold uppercase w-full">
                                Extra resources
                            </div>
                            <div class="flex flex-col bg-indigo-800 text-white p-6 py-12">
                                <div
                                    v-for="(content,index) in contentFromBase['extra_content']"
                                    :key="index"
                                    class="py-2"
                                >
                                    <div v-html="content.data.item[0].content" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/3 flex flex-col">
                        <SoftwareSingleCuratedContent />
                    </div>
                </template>
                <template v-else-if="activeSubmenu === 'access'">
                    <div> Welcome to how to access sub page</div>
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
}


.extraContentHeading {
    display: flex;
    flex-direction: row;
}

.extraContentHeading:after {
    content: "";
    flex: 1 1;
    border-bottom: 1px solid;
    margin: auto;
}

.extraContentHeading:before {
    margin-right: 10px
}

.extraContentHeading:after {
    margin-left: 10px
}

</style>
