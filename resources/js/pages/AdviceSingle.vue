<script setup>

import BaseHero from "@/js/components/bases/BaseHero.vue";
import AdviceSingleExtraContentRenderer from "@/js/components/advice/AdviceSingleExtraContentRenderer.vue";
import AdviceSingleCuratedContent from "@/js/components/advice/AdviceSingleCuratedContent.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";

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
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    const formattedDate = dateObj.toLocaleDateString(undefined, options);

    return formattedDate !== 'Invalid Date' ? formattedDate : '';
}

</script>
<template>
    <BaseSingle content-type="advice">
        <template #hero="{ contentFromBase }">
            <BaseHero
                class="h-[800px]"
                :background-url="contentFromBase['cover_image']"
            >
                <template #titleText>
                    {{ contentFromBase['title'] }}
                </template>
                <template #authorName>
                    {{ contentFromBase['author']['author_name'] ? contentFromBase['author']['author_name'] :
                        contentFromBase['author'] }}
                </template>
                <template #contentDate>
                    {{ timeFormatter(contentFromBase['modified_at']) }}
                </template>
                <!-- <template #subtitleText1>
                    {{ timeFormatter(contentFromBase['post_date']) }}
                </template> -->
                <template #subtitleText2>
                    <div v-html="contentFromBase['excerpt']" />
                </template>
            </BaseHero>
        </template>

        <template #content="{ contentFromBase, recommendationFromBase }">
            <div class="adviceSingleContent flex flex-col mt-14 overflow-hidden p-4 px-8 w-full xl:!flex-row">
                <!--    Content of the Advice    -->
                <div class="flex flex-col flex-wrap px-2 py-4 w-full xl:!w-2/3">
                    <div class="flex font-bold text-2xl uppercase">
                        Getting started
                    </div>
                    <div
                        class="flex content-paragraph flex-col max-w-full overflow-hidden text-lg"
                        v-html="contentFromBase['content']"
                    />
                    <template
                        v-for="(content, index) in contentFromBase['extra_content']"
                        :key="index"
                    >
                        <AdviceSingleExtraContentRenderer :content="content" />
                    </template>
                </div>
                <!--      Curated Content      -->
                <div class="flex flex-col w-full xl:!w-1/3">
                    <AdviceSingleCuratedContent :recommendation-from-base="recommendationFromBase" />
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
