<script setup>
import {ref} from 'vue'
import {useRouter} from "vue-router";

import BaseBreadcrumb from "@/js/components/bases/BaseBreadcrumb.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseSingleProfilePicture from "@/js/components/bases/BaseSingleProfilePicture.vue";
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";
import TinyMceContentRenderer from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceContentRenderer.vue";
import LabelRowContentDisplay from "@/js/components/global/LabelRowContentDisplay.vue";
import ExtraResourceTemplateDisplay from "@/js/components/renderer/ExtraResourceTemplateDisplay.vue";
import SoftwareIconGenerator from "@/js/components/software/SoftwareIconGenerator.vue";
import SoftwareSingleCuratedContent from "@/js/components/software/softwareSingle/SoftwareSingleCuratedContent.vue";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {edSparkContentSanitizer} from "@/js/helpers/objectHelpers";
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
    router.push(`/${author_type}/${author_id}`)
}
/**
 * End of submenu specific code  plus @emit-active-tab-to-specific-page in BaseSingle
 * */
const colorTheme = ref('technologyPurple')

</script>

<template>
    <BaseSingle
        content-type="software"
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
                        parent-page="Apps and Programs"
                        :parent-page-link="'browse/software'"
                        :color-theme="colorTheme"
                    />
                </template>
                <template #titleText>
                    {{ contentFromBase['title'] }}
                </template>
                <template #authorName>
                    <div
                        v-if="contentFromBase['author'] && contentFromBase['author']"
                        class="EventHeroAuthorContainer flex flex-col"
                    >
                        <div class="flex items-center flex-row gap-4">
                            <BaseSingleProfilePicture
                                :author-name="contentFromBase['author']['author_name']"
                                :author-logo-url="String(contentFromBase['author']['author_logo'])"
                            />
                            <div class="authorName flex justify-center flex-col">
                                <div class="mb-2 text-xl">
                                    {{ contentFromBase['author']['author_name'] }}
                                </div>
                                <!--   For now, Only non-user (partners only) can be viewed. Still working on Partner Profile   -->
                                <!--                                <div-->
                                <!--                                    v-if="!(contentFromBase['author']['author_type'] === 'user')"-->
                                <!--                                    class="hover:cursor-pointer hover:text-red-200"-->
                                <!--                                >-->
                                <!--                                    <button-->
                                <!--                                        @click="() => handleClickViewProfile(contentFromBase['author']['author_id'],contentFromBase['author']['author_type'])"-->
                                <!--                                    >-->
                                <!--                                        View profile-->
                                <!--                                    </button>-->
                                <!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </template>
                <template #contentDate>
                    <div class="font-light my-4">
                        {{ formatDateToDayTime(contentFromBase['modified_at']) }}
                    </div>
                </template>
                <template #subtitleText2>
                    <LabelRowContentDisplay :labels-array="contentFromBase['labels']" />
                </template>


                <!--  Selectable sub menu    -->
                <template #submenu>
                    <div class="cursor-pointer flex flex-row gap-4 mb-[-1px] softwareSubmenu z-40">
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
                class="flex flex-col overflow-hidden pt-0 px-5 softwareSingleContent w-full lg:!px-10"
            >
                <template
                    v-if="activeSubmenu === 'detail'"
                >
                    <div class="flex flex-col mr-10 px-2 py-4 w-full">
                        <div
                            class="flex flex-col max-w-full overflow-hidden text-lg"
                        >
                            <TinyMceContentRenderer :raw-content="contentFromBase['content']" />
                        </div>
                        <div
                            v-if="contentFromBase['extra_content'] && contentFromBase['extra_content'].length"
                            class="extraResourcesContainer mt-4 w-full"
                        >
                            <ExtraResourceTemplateDisplay :content="contentFromBase['extra_content']" />
                        </div>
                    </div>
                    <div class="flex flex-col w-full">
                        <SoftwareSingleCuratedContent />
                    </div>
                </template>
                <template v-else-if="activeSubmenu === 'access'">
                    <div
                        v-if="contentFromBase['how_to_access']"
                        class="
                            flex
                            flex-col
                            mt-10
                            overflow-hidden
                            pt-0
                            px-5
                            richTextContentContainer
                            softwareSingleHowToAccess
                            "
                        v-html="edSparkContentSanitizer(contentFromBase['how_to_access'])"
                    />
                    <div
                        v-else
                        class="flex flex-col mt-10 overflow-hidden pt-0 px-5 softwareSingleHowToAccess"
                    >
                        No information provided.
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
        list-style: disc;
    }
}

.extraContentEachContainer {
    ul {
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