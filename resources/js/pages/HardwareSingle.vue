<script setup>
import BaseHero from '@/js/components/bases/BaseHero.vue';
import BaseSingle from '@/js/components/bases/BaseSingle.vue';
import HardwareCarousel from '@/js/components/hardware/HardwareCarousel.vue';
import GenericCard from '../components/card/GenericCard.vue';

import axios from 'axios';
import {ref, onBeforeMount, onMounted} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import {useHardwareStore} from '../stores/useHardwareStore.js';

const hardwareStore = useHardwareStore();
const recommendedResources = ref([]);
const baseContentRef = ref(null);
const route = useRoute();
const router = useRouter();

const likeBookmarkData = {
    post_id: route.params.id,
    user_id: 2,
    post_type: 'hardware'
};

const loadRecommendedResources = async () => {
    recommendedResources.value = await hardwareStore.loadAllArticles();
};

loadRecommendedResources();

const handleClickHardwareCard = (resource) => {
    router.push({
        name: 'hardware-single',
        params: {
            id: resource.id,
            content: JSON.stringify(resource)
        }
    })
}

</script>

<template>
    <BaseSingle
        content-hero="hardware"
        :content-type="'hardware'"
    >
        <template #hero="{contentFromBase}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
            >
                <template #titleText>
                    {{ contentFromBase['product_name'] }}
                </template>
                <template
                    v-if="contentFromBase['brand']"
                    #hardwareProvider
                >
                    <div>
                        <p class="text-[15px] font-medium">
                            {{ contentFromBase['brand']['brandName'] }}
                        </p>
                    </div>
                </template>
                <template #subtitleText2>
                    <div v-html="contentFromBase['product_excerpt']" />
                </template>
            </BaseHero>
        </template>

        <template #content="{ contentFromBase }">
            <div
                :id="contentFromBase['id']"
                ref="baseContentRef"
                class="py-4 px-20 flex flex-col w-full overflow-hidden bg-slate-200"
            >
                <!-- Carousel here -->
                <HardwareCarousel
                    :slide-items="contentFromBase"
                />
                <div class="flex flex-row mt-[64px] gap-12">
                    <div class="w-2/3 flex flex-col py-4">
                        <div>
                            <h1
                                class="text-2xl flex font-bold uppercase"
                            >
                                {{ contentFromBase['product_name'] }}
                            </h1>
                        </div>
                        <div
                            class="pt-8 text-lg flex flex-col content-paragraph overflow-hidden max-w-full"
                            v-html="contentFromBase['product_content']"
                        />
                    </div>

                    <div
                        v-if="contentFromBase['brand']"
                        class="w-1/3"
                    >
                        <div
                            v-if="recommendedResources"
                            class="bg-[#048246]/5 flex flex-col px-6 py-6 gap-6"
                        >
                            <h3 class="text-[24px] font-bold mx-auto pb-8">
                                More from {{ contentFromBase['brand']['brandName'] }}
                            </h3>
                            <div
                                v-for="item in recommendedResources.slice(0,2)"
                                class="flex justify-between"
                            >
                                <GenericCard
                                    v-if="item['brand']['brandName'] === contentFromBase['brand']['brandName']"
                                    class="mx-auto bg-white"
                                    :title="item['product_name']"
                                    :cover-image="item['cover_image']"
                                    :number-per-row="1"
                                    :display-author="item['brand']['brandName']"
                                    :display-content="item['product_excerpt']"
                                    :like-bookmark-data="likeBookmarkData"
                                    @click="handleClickHardwareCard(item)"
                                />
                            </div>
                        </div>
                    </div>
                </div>
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
