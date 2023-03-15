<script setup>
    /**
     * IMPORT DEPENDENCIES
     */
import { useRouter, useRoute } from 'vue-router';

/**
 * IMPORT COMPONENTS
 */
import SchoolsProfile from '../components/schools/SchoolsProfile.vue';
import OurStory from '../components/schools/OurStory.vue';
/**
 * IMPORT SVGS
 */
import SchoolsSubMenu from '../components/svg/SchoolsSubMenu.vue';
import ChevronRight from '../components/svg/ChevronRight.vue';

const route = useRoute();
const router = useRouter();

const urlOrigin = window.location.origin
const breadCrumbPrev = 'Schools'
const breadCrumbName = route.params.name

</script>

<template>
    <div class="-mt-[140px]">
        <SchoolsProfile>
            <template #hero>
                <div class="px-[48px] bg-[url(http://localhost:5173/resources/assets/images/adelaide-high-school.png)] h-[680px] w-full bg-center bg-no-repeat bg-cover">
                    <div class="h-full w-full grid grid-cols-12">
                        <div class="col-span-7 flex mt-[190px]">
                            <div class="flex flex-row gap-2 h-fit place-items-center">
                                <router-link to="/"> 
                                    <p class="text-[14px] text-white hover:text-[#44B8F3]">
                                        Home
                                    </p>
                                </router-link>
                                <ChevronRight /> 
                                <router-link to="/schools">
                                    <p class="text-[14px] text-white hover:text-[#44B8F3]">
                                        {{ breadCrumbPrev }}
                                    </p>
                                </router-link>
                                <ChevronRight />
                                <p class="text-[14px] text-[#44B8F3]">
                                    {{ breadCrumbName }}
                                </p>
                            </div>
                        </div>
                        <div class="col-span-5" />
                    </div>
                </div>
            </template>
        </SchoolsProfile>

        <div class="-mt-[180px]">
            <SchoolsSubMenu />

            <OurStory />
        </div>
    </div>
</template>
