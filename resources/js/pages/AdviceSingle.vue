<script setup>

import AdviceSingleCuratedContent from "@/js/components/advice/AdviceSingleCuratedContent.vue";
import BaseBreadcrumb from "@/js/components/bases/BaseBreadcrumb.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import TinyMceContentRenderer from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceContentRenderer.vue";
import LabelRowContentDisplay from "@/js/components/global/LabelRowContentDisplay.vue";
import ExtraResourceTemplateDisplay from "@/js/components/renderer/ExtraResourceTemplateDisplay.vue";
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
                        parent-page="guide"
                    />
                </template>
                <template #titleText>
                    {{ contentFromBase['title'] }}
                </template>
                <template #authorName>
                    {{
                        contentFromBase['author']['author_name'] ? contentFromBase['author']['author_name'] :
                        contentFromBase['author']
                    }}
                </template>
                <template #contentDate>
                    {{ timeFormatter(contentFromBase['modified_at']) }}
                </template>
                <!-- <template #subtitleText1>
                    {{ timeFormatter(contentFromBase['post_date']) }}
                </template> -->
                <template #subtitleText2>
                    <div v-html="edSparkContentSanitizer(contentFromBase['excerpt'])" />
                    <LabelRowContentDisplay :labels-array="contentFromBase['labels']" />
                </template>
            </BaseHero>
        </template>

        <template #content="{ contentFromBase }">
            <div class="adviceSingleContent flex flex-col overflow-hidden px-8 w-full xl:!flex-row">
                <!--    Content of the Advice    -->
                <div class="flex flex-col flex-wrap mr-10 px-2 py-2 richTextContentContainer w-full xl:!w-2/3">
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
                <div class="flex flex-col w-full xl:!w-1/3">
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
