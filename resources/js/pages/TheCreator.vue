<script setup lang="ts">

import {computed} from "vue";
import {useRoute, useRouter} from "vue-router";

import CreateSelector from "@/js/components/selector/CreateSelector.vue";

const router = useRouter()
const route = useRoute()


console.log(route.name) // createAdvice, createSoftware, createEvent

const handleClickBackToPosts = () :void =>{
    router.push('/create')
}

const showBackToPosts = computed(() => {
    console.log(route.name)
    return route.name !== 'userPosts'

})
</script>

<template>
    <div class="mt-12 mx-5 theCreatorContainer md:!mx-10 lg:!mx-20">
        <div class="flex flex-col">
            <div class="TheCreatorPageTitle flex items-center flex-col">
                <div
                    v-if="showBackToPosts"
                    class="backToPosts font-base text-sm hover:text-main-teal hover:cursor-pointer"
                    @click="handleClickBackToPosts"
                >
                    Back to posts
                </div>
                <div
                    v-else
                    class="h-5"
                >
                    {{ "" }}
                </div>
                <div class="CreatorTitle font-semibold mb-4 text-xl">
                    What would you like to create?
                </div>
            </div>
            <CreateSelector />
        </div>

        <router-view />
    </div>
</template>
