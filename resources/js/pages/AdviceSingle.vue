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
        <template #hero="{contentFromBase}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
            >
                <template #titleText>
                    {{ contentFromBase['post_title'] }}
                </template>
                <template #authorName>
                    {{ contentFromBase['author']['author_name'] }}
                </template>
                <template #contentDate>
                    {{ timeFormatter(contentFromBase['post_date']) }}
                </template>
                <!-- <template #subtitleText1>
                    {{ timeFormatter(contentFromBase['post_date']) }}
                </template> -->
                <template #subtitleText2>
                    <div v-html="contentFromBase['post_excerpt']" />
                </template>
            </BaseHero>
        </template>

        <template #content="{contentFromBase,recommendationFromBase}">
            <div
                class="adviceSingleContent p-4 px-8 flex flex-row w-full overflow-hidden mt-14"
            >
                <!--    Content of the Advice    -->
                <div class="w-2/3 flex flex-col flex-wrap py-4 px-2">
                    <div class="text-2xl flex font-bold uppercase">
                        Getting started
                    </div>
                    <div
                        class="text-lg flex flex-col content-paragraph overflow-hidden max-w-full"
                        v-html="contentFromBase['post_content']"
                    />
                    <template
                        v-for="(content,index) in contentFromBase['extra_content']"
                        :key="index"
                    >
                        <AdviceSingleExtraContentRenderer :content="content" />
                    </template>
                </div>
                <!--      Curated Content      -->
                <div class="w-1/3 flex flex-col">
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
