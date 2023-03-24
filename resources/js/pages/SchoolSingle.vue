<script setup>
    /**
     * IMPORT DEPENDENCIES
     */
import { useRouter, useRoute } from 'vue-router';
import {ref} from 'vue'
import axios from 'axios'
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
const serverURL = import.meta.env.VITE_SERVER_URL_API


const urlOrigin = window.location.origin
const breadCrumbPrev = 'Schools'
const breadCrumbName = route.params.name

const schoolContent = ref({})

onBeforeMount( async () =>{
    // TODO Erick - Replace with get one school instead of all then filter.
    await axios.get(`${serverURL}/fetchAllSchools`).then(res => {
        schoolContent.value = res.data.filter(school => school.name === route.params.name.replace('%20', ' ' ))[0]
        console.log('successfully saved school content')
    }).catch(err => {
        console.log('uhh ohh something occured during the attempt of saving school info')
        console.log(err)
    })

})
    
const handleSaveNewSchoolInfo = async (content_blocks, tech_used) => {
    const body = Object.assign({},schoolContent.value)
    body.content_blocks = content_blocks
    body.tech_used  = tech_used
    console.log(body)
    await axios.post(`${serverURL}/updateSchool`, body).then(res =>{        // assign school info with newest data that has been saved succesfully to trigger update
        schoolContent.value = _.cloneDeep(body)
    }).catch(err =>{
        console.log(err)
        console.log('Something wrong while attempting to post ')

    })
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
        <div class="flex flex-row w-full">
            <div
                v-if="Object.keys(schoolContent).length > 1"
                class="school-content py-2 px-10 flex w-full"
            >
                <SchoolContent
                    :school-content="schoolContent"
                    @send-info-to-parent="handleSaveNewSchoolInfo"
                />
            </div>
        </div>
    </div>
<!--    <button-->
<!--        class="w-24 h-12 bg-slate-500"-->
<!--        @click="handleAddNewSchool"-->
<!--    >-->
<!--        Add new school-->
<!--    </button>-->
</template>
