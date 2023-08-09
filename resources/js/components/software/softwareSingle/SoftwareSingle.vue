+<script setup>
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import SoftwareSingleCuratedContent from "@/js/components/software/softwareSingle/SoftwareSingleCuratedContent.vue";
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";
import {ref} from 'vue'
import {findNestedKeyValue} from "@/js/helpers/objectHelpers";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {imageURL} from "@/js/constants/serverUrl";
import {useRouter} from "vue-router";
import SoftwareIconGenerator from "@/js/components/software/SoftwareIconGenerator.vue";
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
const router = useRouter()

// handleChangeSubmenu will be triggered by emit from BaseSingle
const handleChangeSubmenu = (value) => {
    activeSubmenu.value = value
    console.log('active submenu has been changed to ', value)
}
/**
 *  Visit profile from sinle page
 */
const handleClickViewProfile = (author_id, author_type) => {
    router.push(`/${author_type}/${author_id}` )
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
                <template #authorName>
                    <div
                        v-if="contentFromBase['author'] && contentFromBase['author']"
                        class="EventHeroAuthorContainer flex flex-col"
                    >
                        <div class="flex items-center flex-row">
                            <div class="flex items-center h-20 mx-4 smallPartnerLogo w-24">
                                <img
                                    :src="`${imageURL}/${String(contentFromBase['author']['author_logo'])}`"
                                    :alt=" contentFromBase['author']['author_name'] + ' logo'"
                                    class="bg-center h-24 object-contain rounded-full w-24"
                                >
                            </div>
                            <div class="authorName flex flex-col pt-6">
                                <div class="mb-2 text-2xl">
                                    {{ contentFromBase['author']['author_name'] }}
                                </div>
                                <!--   For now, Only non-user (partners only) can be viewed. Still working on Partner Profile   -->
                                <div
                                    v-if="!(contentFromBase['author']['author_type'] === 'user')"
                                    class="hover:cursor-pointer hover:text-red-200"
                                >
                                    <button @click="() => handleClickViewProfile(contentFromBase['author']['author_id'],contentFromBase['author']['author_type'])">
                                        View Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>


                <template #subtitleText1>
                    <div class="font-semibold pt-2">
                        {{ formatDateToDayTime(contentFromBase['post_date'] ) }}
                    </div>
                </template>
                <template #subtitleText2>
                    <div v-html="contentFromBase['post_excerpt']" />
                </template>
                <template #subtitleContent>
                    <div class="SoftwareTypeInfoInHero flex flex-row gap-4 mt-4">
                        <SoftwareIconGenerator
                            :icon-name="contentFromBase['software_type'][0]"
                            class="h-14 w-14"
                        />
                        <p class="flex justify-center items-center font-light">
                            {{ contentFromBase['software_type'][0] }}
                        </p>
                    </div>
                </template>

                <!--  Selectable sub menu    -->
                <template #submenu>
                    <div class="cursor-pointer flex flex-row gap-4 softwareSubmenu z-40">
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
                class="flex flex-col mt-10 overflow-hidden pt-0 px-5 softwareSingleContent w-full lg:!px-10 xl:!flex-row"
            >
                <template
                    v-if="activeSubmenu === 'detail'"
                >
                    <div class="flex flex-col flex-wrap px-2 py-4 w-full xl:!w-2/3">
                        <div class="flex font-bold py-4 text-2xl uppercase">
                            Getting started
                        </div>
                        <div
                            class="flex content-paragraph flex-col max-w-full overflow-hidden text-lg"
                            v-html="contentFromBase['post_content']"
                        />
                        <div
                            v-if="contentFromBase['extra_content'] && contentFromBase['extra_content'].length"
                            class="extraResourcesContainer"
                        >
                            <div class="extraContentHeading flex font-bold py-4 text-2xl uppercase w-full">
                                Extra resources
                            </div>
                            <div class="bg-indigo-800 flex flex-col px-6 py-2 text-white">
                                <div
                                    v-for="(extra_content,index) in contentFromBase['extra_content']"
                                    :key="index"
                                    class="py-2"
                                >
                                    <template v-if="findNestedKeyValue(extra_content,'item')">
                                        <div
                                            v-for="(item, index) in findNestedKeyValue(extra_content,'item')"
                                            :key="index"
                                            class="extraContentEachContainer mb-6"
                                        >
                                            <div
                                                v-for="(innerItem, innerIndex) in item"
                                                :key="innerIndex"
                                            >
                                                <p class="font-semibold text-white text-xl">
                                                    {{ innerItem.heading }}
                                                </p>
                                                <div v-html="innerItem.content" />
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full xl:!w-1/3">
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
    ul{
        list-style: disc;
    }
}
.extraContentEachContainer{
    ul{
        list-style: disc;
        margin-left: 12px
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