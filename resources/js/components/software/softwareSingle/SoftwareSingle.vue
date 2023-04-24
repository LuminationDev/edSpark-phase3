<script setup>
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";

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

</script>

<template>
    <BaseSingle content-type="software">
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
                    <div v-html="contentFromBase['post_excerpt']" />
                </template>
            </BaseHero>
        </template>
        <template #content="{contentFromBase}">
            <div v-html="contentFromBase['post_content']" />
        </template>
    </BaseSingle>
</template>