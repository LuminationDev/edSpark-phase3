`<script setup>

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
                <template #subtitleText1>
                    {{ contentFromBase['post_date'] }}
                </template>
                <template #subtitleText2>
                    {{ contentFromBase['post_excerpt'] }}
                </template>
            </BaseHero>
        </template>

        <template #content="{contentFromBase}">
            <div
                class="adviceSingleContent p-4 px-8 flex flex-row w-full overflow-hidden"
            >
                <!--    Content of the Advice    -->
                <div class="w-2/3 flex flex-col flex-wrap border-2">
                    <div class="text-2xl flex font-bold uppercase">
                        Getting started
                    </div>
                    <div
                        class="text-lg flex content-paragraph overflow-hidden max-w-full"
                        v-html="contentFromBase['post_content']"
                    />
                    <div class="text-lg">
                        Some other content need to wait for data from backend
                    </div>
                    <div
                        v-for="(content,index) in contentFromBase['extra_content']"
                        :key="index"
                        class="extraContent"
                    >
                        <AdviceSingleExtraContentRenderer :content="content" />
                    </div>
                </div>
                <!--      Curated Content      -->
                <div class="w-1/3 flex flex-col">
                    <AdviceSingleCuratedContent />
                </div>
            </div>
        </template>
    </BaseSingle>
</template>