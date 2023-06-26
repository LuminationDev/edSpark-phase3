<script setup>
import {ref, computed, onMounted} from 'vue'
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import {imageURL} from "@/js/constants/serverUrl";
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";

const props = defineProps({

})

const emits = defineEmits([])
const availableSubmenu = ref([])

const formattedSubmenuData = computed(() =>{
    if(!availableSubmenu.value || availableSubmenu.value.length === 0){
        return []
    }else{
        return availableSubmenu.value.map(menu =>{
            return {
                displayText : menu.charAt(0).toUpperCase() + menu.slice(1),
                value: menu
            }
        })

    }
})

const activeSubMenu = ref(availableSubmenu.value[0] || '')

const handleChangeSubmenu = (value) => {
    activeSubMenu.value = value
}

const handleEmittedAvailableSubmenu = (value) =>{
    console.log('handleEmitIsCalled')
    console.log(value)
    availableSubmenu.value = value.split(',')
    activeSubMenu.value = availableSubmenu.value[0]
}

</script>

<template>
    <BaseSingle
        content-type="partner"
        @emit-available-submenu="handleEmittedAvailableSubmenu"
    >
        <template #hero="{contentFromBase}">
            <BaseHero
                :background-url="contentFromBase['cover_image']"
            >
                <template #titleText>
                    <div class="flex flex-row">
                        <div class="smallPartnerLogo w-16 h-16">
                            <img
                                :src="`${imageURL}/${contentFromBase['logo']}`"
                                alt="logo"
                            >
                        </div>
                        <span>
                            {{ contentFromBase['name'] }}

                        </span>
                    </div>
                </template>
                <template #authorName>
                    {{ contentFromBase['name'] }}
                </template>
                <template #subtitleText1>
                    {{ contentFromBase['motto'] }}
                </template>
                <template #subtitleText2>
                    {{ contentFromBase['introduction'] }}
                </template>
                <template #submenu>
                    <BaseSingleSubmenu
                        :active-subpage="activeSubMenu"
                        :emit-to-base="handleChangeSubmenu"
                        :menu-array="formattedSubmenuData"
                    />
                </template>
            </BaseHero>
        </template>

        <!--        <template #content="{contentFromBase}">-->
        <!--            <div-->
        <!--                class="adviceSingleContent p-4 px-8 flex flex-row w-full overflow-hidden mt-14"-->
        <!--            >-->
        <!--                &lt;!&ndash;    Content of the Advice    &ndash;&gt;-->
        <!--                <div class="w-2/3 flex flex-col flex-wrap py-4 px-2">-->
        <!--                    <div class="text-2xl flex font-bold uppercase">-->
        <!--                        Getting started-->
        <!--                    </div>-->
        <!--                    <div-->
        <!--                        class="text-lg flex flex-col content-paragraph overflow-hidden max-w-full"-->
        <!--                        v-html="contentFromBase['post_content']"-->
        <!--                    />-->
        <!--                    <template-->
        <!--                        v-for="(content,index) in contentFromBase['extra_content']"-->
        <!--                        :key="index"-->
        <!--                    >-->
        <!--                        <AdviceSingleExtraContentRenderer :content="content" />-->
        <!--                    </template>-->
        <!--                </div>-->
        <!--                &lt;!&ndash;      Curated Content      &ndash;&gt;-->
        <!--                <div class="w-1/3 flex flex-col">-->
        <!--                    <AdviceSingleCuratedContent />-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </template>-->
    </BaseSingle>
</template>
