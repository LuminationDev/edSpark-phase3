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

const handleAddNewSchool = async () => {
    const newSchoolData =
        {
            "site_id" : "5", //this id if you have imported the excel sheet in site
            "owner_id": "1", // 1- jake, 2-Erick, 3-Asim
            "name": "Adelaide Botanical High Schoool",
            "content_blocks":
                {
                    "time": 1678961115519,
                    "blocks": [
                        {
                            "id": "bFYQTCRyxO",
                            "type": "header",
                            "data": {"text": "<b><font size=\"6\">About Adelaide High School</font></b>", "level": 2}
                        }, {
                            "id": "Ut01dKhFzb",
                            "type": "paragraph",
                            "data": {"text": "Adelaide High maintains a 1:1 laptop program. Newly enrolled students are expected to purchase a device through the school. We are a M365 school and use Daymap. STEM is a strong focus and makes up one of two of the compulsory middle year subjects."}
                        }, {
                            "id": "inGB8KadHC",
                            "type": "header",
                            "data": {"text": "<a href=\"http://www.google.com\">Additional Information</a>", "level": 2}
                        }, {
                            "id": "Jxk9pSC9vS",
                            "type": "paragraph",
                            "data": {"text": "<font size=\"5\">Here comes another paragrah regarding the exellency of Adelaied high school with 98% high rating blabla</font>"}
                        }, {
                            "id": "M7o2TjPh0d",
                            "type": "header",
                            "data": {"text": "<i>Our values: </i>", "level": 2}
                        }, {
                            "id": "dA4Jagclhf",
                            "type": "list",
                            "data": {
                                "style": "unordered",
                                "items": ["We are one of the best HighSchool in Adelaide", "<font size=\"7\">We are proud of our teaching qualities</font>", "we put forward our practical approach to most of the curriculum", "We do not compromise under no circumstances"]
                            }
                        },
                        {
                            "id": "I_AlVcagery",
                            "type": "image",
                            "data": {
                                "url": "https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.jpg",
                                "caption": "Roadster // tesla.com",
                                "withBorder": false,
                                "withBackground": false,
                                "stretched": true
                            }
                        }
                    ],
                    "version": "2.26.5"
                },
            "logo": "https://via.placeholder.com/640x480.png/00ccee?text=quia",
            "cover_image": "https://via.placeholder.com/640x480.png/00ccee?text=quia",
            "tech_used": {
                "time" : 1550476186479,
                "blocks" : [
                    {
                        "type" : "paragraph",
                        "data" : {
                            "text" : "The example of text that was written in <b>one of popular</b> text editors."
                        }
                    },
                    {
                        "type" : "header",
                        "data" : {
                            "text" : "With the header of course",
                            "level" : 2
                        }
                    },
                    {
                        "type" : "paragraph",
                        "data" : {
                            "text" : "So what do we have?"
                        }
                    }
                ],
                "version" : "2.8.1"
            },
            "pedagogical_approaches": {
                "time" : 1550476186479,
                "blocks" : [
                    {
                        "type" : "paragraph",
                        "data" : {
                            "text" : "The example of text that was written in <b>one of popular</b> text editors."
                        }
                    },
                    {
                        "type" : "header",
                        "data" : {
                            "text" : "With the header of course",
                            "level" : 2
                        }
                    },
                    {
                        "type" : "paragraph",
                        "data" : {
                            "text" : "So what do we have?"
                        }
                    }
                ],
                "version" : "2.8.1"
            },
            "tech_landscape": {
                "time" : 1550476186479,
                "blocks" : [
                    {
                        "type" : "paragraph",
                        "data" : {
                            "text" : "The example of text that was written in <b>one of popular</b> text editors."
                        }
                    },
                    {
                        "type" : "header",
                        "data" : {
                            "text" : "With the header of course",
                            "level" : 2
                        }
                    },
                    {
                        "type" : "paragraph",
                        "data" : {
                            "text" : "So what do we have?"
                        }
                    }
                ],
                "version" : "2.8.1"
            }
        }
    await axios.post('http://localhost:8000/api/createSchool', newSchoolData).then(res => {
        console.log(res)
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
        <div class="flex flex-row">
            <div class="school-content py-2 px-10 basis-2/3">
                <SchoolContent />
            </div>
            <div class="school-tech basis-1/3">
                <SchoolTech :tech-list="tempSchoolData.techUsed" />
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
