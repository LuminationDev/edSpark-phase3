<script setup>

import AdviceSingleCuratedContent from "@/js/components/advice/AdviceSingleCuratedContent.vue";
import BaseBreadcrumb from "@/js/components/bases/BaseBreadcrumb.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseSingleProfilePicture from "@/js/components/bases/BaseSingleProfilePicture.vue";
import TinyMceContentRenderer from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceContentRenderer.vue";
import LabelRowContentDisplay from "@/js/components/global/LabelRowContentDisplay.vue";
import ExtraResourceTemplateDisplay from "@/js/components/renderer/ExtraResourceTemplateDisplay.vue";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {edSparkContentSanitizer} from "@/js/helpers/objectHelpers";
/**
 *  type AdviceSingleContent = {
 *      post_id: number
 *      advice_type: string,
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

const timeFormatter = (originalFormat) => {
    const dateObj = new Date(originalFormat);
    const options = {year: 'numeric', month: 'short', day: 'numeric'};
    const formattedDate = dateObj.toLocaleDateString(undefined, options);

    return formattedDate !== 'Invalid Date' ? formattedDate : '';
}

</script>
<template>
    <BaseSingle content-type="advice">
        <template #hero="{ contentFromBase }">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
            >
                <template #breadcrumb>
                    <BaseBreadcrumb
                        :child-page="contentFromBase.title"
                        parent-page="Guides"
                        :parent-page-link="'browse/guide'"
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
                    <div
                        v-html="edSparkContentSanitizer(contentFromBase['excerpt'])"
                    />
                    <LabelRowContentDisplay :labels-array="contentFromBase['labels']" />
                </template>
            </BaseHero>
        </template>

        <template #content="{ contentFromBase }">
            <div class="adviceSingleContent flex flex-col overflow-hidden px-8 w-full">
                <!--    Content of the Advice    -->
                <div class="flex flex-col flex-wrap mb-10 mr-10 px-2 py-2 richTextContentContainer w-full">
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
                <!--      Curated Content      -->
                <div class="flex flex-col w-full">
                    <AdviceSingleCuratedContent />
                </div>
            </div>
        </template>
    </BaseSingle>
</template>

<style lang="scss">
.adviceSingleContent {
    figure {
        margin: 20px 0;

        a {
            img {
                max-height: 400px !important;
                width: auto;
            }
        }
    }

    figcaption {
        display: none;
    }
}
</style>
