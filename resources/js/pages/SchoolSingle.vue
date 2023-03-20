<script setup>
    /**
     * IMPORT DEPENDENCIES
     */
import { useRouter, useRoute } from 'vue-router';
import {ref} from 'vue'

/**
 * IMPORT COMPONENTS
 */
import SchoolsProfile from '../components/schools/SchoolsProfile.vue';
import OurStory from '../components/schools/OurStory.vue';
import SchoolContent from "@/js/components/schoolsingle/SchoolContent.vue";
import SchoolTech from "@/js/components/schoolsingle/SchoolTech.vue";
/**
 * IMPORT SVGS
 */
import SchoolsSubMenu from '../components/svg/SchoolsSubMenu.vue';
import ChevronRight from '../components/svg/ChevronRight.vue';
import {onBeforeMount} from "vue";

const route = useRoute();
const router = useRouter();

const urlOrigin = window.location.origin
const breadCrumbPrev = 'Schools'
const breadCrumbName = route.params.name

const schoolData = ref({})
onBeforeMount( () =>{

})

/**
 * No receiving props into this. do query instead to get data and render them out
 * Need to pass props into School profile image, content and tech
 */
const tempSchoolData = {
    title: 'Adelaide Botanic High School',
    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Nam et arcu facilisis, blandit felis ut, egestas dolor.',
    created_at: '29th Jan 2023',
    cover: 'https://picsum.photos/200/300',
    techUsed: [
        {
            name: 'VR',
            description: 'is a simulated experience that employs pose tracking and 3D near-eye displays to give the user an immersive feel of a virtual world.',
            category: 'Emerging tech',
        },
        {
            name: 'AR',
            description: '(Augmented Reality) is an interactive experience that combines the real world and computer-generated content.',
            category: 'Emerging tech',
        },
        {
            name: '3D Printing',
            description: '3D Printing or additive manufacturing is the construction of a three-dimensional object from a CAD model or a digital 3D model.',
            category: 'Emerging tech',
        },
        {
            name: 'Microsoft Teams',
            description: 'is a proprietary business communication platform developed by Microsoft, as part of the Microsoft 365 family of products.',
            category: 'Platforms',
        },
    ]
}


</script>

<template>
    <div class="-mt-[140px] flex flex-col ">
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
                                <!-- TODO: Breadcrumb builder -- gotta be smart -->
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

        <div class="-mt-[180px] mb-20">
            <SchoolsSubMenu />
        </div>
        <div class="flex flex-row">
            <div class="school-content py-2 px-10 basis-2/3">
                <SchoolContent />
            </div>
            <div class="school-tech basis-1/3">
                <SchoolTech :tech-list="tempSchoolData.techUsed" />
            </div>
        </div>
    </div>
</template>
