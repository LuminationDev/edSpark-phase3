<script>
    import { computed } from 'vue';
    import DashboardHero from '../components/dashboard/DashboardHero.vue';
    import SectionHeader from '../components/global/SectionHeader.vue';
    import ContentSection from '../components/global/ContentSection.vue';
    import axios from 'axios';

    export default {
        setup() {

        },

        components: {
            DashboardHero,
            SectionHeader,
            ContentSection,

        },

        data() {
            return {
                posts: [
                    {
                        title: 'Im a Post',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Quisque quis luctus turpis. Nam et arcu facilisis, blandit felis ut, egestas dolor. Cras at dignissim augue. Curabitur placerat fermentum mollis. Vestibulum mollis facilisis placerat.',
                        created_at: '25th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                    },
                    {
                        title: 'Test content title',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus.',
                        created_at: '14th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                    },
                    {
                        title: 'Try out a slightly longer title',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Nam et arcu facilisis, blandit felis ut, egestas dolor.',
                        created_at: '29th Jan 2023',
                        cover: 'https://picsum.photos/200/300',
                    }
                ],
                softwares: [
                    {
                        title: 'Im a Software',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Quisque quis luctus turpis. Nam et arcu facilisis, blandit felis ut, egestas dolor. Cras at dignissim augue. Curabitur placerat fermentum mollis. Vestibulum mollis facilisis placerat.',
                        created_at: '25th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                    },
                    {
                        title: 'Test content title',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus.',
                        created_at: '14th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                    },
                    {
                        title: 'Try out a slightly longer software title',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Nam et arcu facilisis, blandit felis ut, egestas dolor.',
                        created_at: '29th Jan 2023',
                        cover: 'https://picsum.photos/200/300',
                    },
                    {
                        title: 'Software',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Nam et arcu facilisis, blandit felis ut, egestas dolor.',
                        created_at: '29th Jan 2023',
                        cover: 'https://picsum.photos/200/300',
                    }
                ],
                resources: [
                    {
                        title: 'Im a Software',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Quisque quis luctus turpis. Nam et arcu facilisis, blandit felis ut, egestas dolor. Cras at dignissim augue. Curabitur placerat fermentum mollis. Vestibulum mollis facilisis placerat.',
                        created_at: '25th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                    },
                    {
                        title: 'Test content title',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus.',
                        created_at: '14th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                    },
                ],
                schools: [
                    {
                        title: 'School name 1',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Quisque quis luctus turpis. Nam et arcu facilisis, blandit felis ut, egestas dolor. Cras at dignissim augue. Curabitur placerat fermentum mollis. Vestibulum mollis facilisis placerat.',
                        created_at: '25th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                    },
                    {
                        title: 'School name 2',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus.',
                        created_at: '14th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                    },
                    {
                        title: 'School name 2 but longer',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Nam et arcu facilisis, blandit felis ut, egestas dolor.',
                        created_at: '29th Jan 2023',
                        cover: 'https://picsum.photos/200/300',
                    },
                    {
                        title: 'School name 4',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Nam et arcu facilisis, blandit felis ut, egestas dolor.',
                        created_at: '29th Jan 2023',
                        cover: 'https://picsum.photos/200/300',
                    }
                ]
            }
        },

        created() {
            // this.getPosts();
        },

        methods: {
            // async getPosts() {
            //     await axios.get('/api/category')
            //         .then((response) => {
            //             this.posts = response.data;
            //         })
            //         .catch((error) => {
            //             console.log('---ERROR LOGGING---', error);
            //         })
            //     // let response = await axios.get('/api/category');
            //     // console.log(response.data)
            //     // return response.data

            // }
        },

        mounted() {
            // this.getPosts();
            // console.log(this.getPosts);
            // console.log(this.posts)
        }
    }
</script>

<template>
    <div>
        <DashboardHero class="-mt-[140px]" />

        <SectionHeader :classes="'bg-[#339999]'" :section="'events'">
            <template #header>
                <h3 class="text-white text-[36px] font-normal self-center section-header">
                    New Events
                </h3>
            </template>
            <template #cta>
                <button class="bg-white px-4 py-2 rounded-sm border-2 border-[#339999] text-[#339999] text-[15px] font-medium cursor-pointer hover:text-[#307474] hover:border-2 hover:border-[#307474]">
                    View all events
                </button>
            </template>
        </SectionHeader>

        <div class="px-[81px] py-20 h-[800px]">
            <div class="grid grid-cols-3 gap-[24px] w-full">
                <div v-for="post in this.posts" class="col-span-1 bg-white shadow-lg border-[0.5px] border-black cursor-pointer h-[530px] transition-all group hover:shadow-2xl">
                    <ContentSection>
                        <template #cover>
                            <div :class="`bg-[url('${post.cover}')]`" class="h-36 transition-all group-hover:h-0"></div>
                        </template>
                        <template #title>
                            {{ post.title }}
                        </template>
                        <template #created_at>
                            {{ post.created_at }}
                        </template>
                        <template #description>
                            {{  post.description  }}
                        </template>
                    </ContentSection>
                </div>
            </div>
        </div>

        <SectionHeader :classes="'bg-[#1C5CA9]'" :section="'software'">
            <template #header>
                <h3 class="text-white text-[36px] font-normal self-center section-header">
                    Top Software
                </h3>
            </template>
            <template #cta>
                <button class="bg-white px-4 py-2 rounded-sm border-2 border-[#1C5CA9] text-[#1C5CA9] text-[15px] font-medium cursor-pointer hover:text-[#1a3b64] hover:border-2 hover:border-[#1a3b64]">
                    View all software
                </button>
            </template>
        </SectionHeader>

        <div class="px-[81px] py-20">
            <div class="grid grid-cols-2 gap-[24px] w-full h-full">
                <div class="col-span-1">

                </div>

                <div class="col-span-1">
                    <div class="grid grid-cols-2 grid-rows-2 gap-[24px] w-full h-full">
                        <div class="col-span-1 row-span-1 bg-white shadow-lg border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl" v-for="software in this.softwares">
                            <ContentSection>
                                <template #cover>
                                    <div :class="`bg-[url('${software.cover}')]`" class="h-36 transition-all group-hover:h-0"></div>
                                </template>
                                <template #title>
                                    {{ software.title }}
                                </template>
                                <template #created_at>
                                    {{ software.created_at }}
                                </template>
                                <template #description>
                                    {{  software.description  }}
                                </template>
                            </ContentSection>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <SectionHeader :classes="'bg-[#0A7982]'" :section="'advice'">
            <template #header>
                <h3 class="text-white text-[36px] font-normal self-center section-header">
                    Advice
                </h3>
            </template>
            <template #cta>
                <button class="bg-white px-4 py-2 rounded-sm border-2 border-[#0A7982] text-[#0A7982] text-[15px] font-medium cursor-pointer hover:text-[#15464b] hover:border-2 hover:border-[#15464b]">
                    View all resources
                </button>
            </template>
        </SectionHeader>

        <div class="px-[81px] py-20">
            <div class="grid grid-cols-3 gap-[24px] w-full h-full">
                <div class="col-span-1">

                </div>

                <div class="col-span-2">
                    <div class="grid grid-cols-2 grid-rows-1 gap-[24px] w-full h-full">
                        <div class="col-span-1 row-span-1 bg-white shadow-lg border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl" v-for="resource in this.resources">
                            <ContentSection>
                                <template #cover>
                                    <div :class="`bg-[url('${resource.cover}')]`" class="h-36 transition-all group-hover:h-0"></div>
                                </template>
                                <template #title>
                                    {{ resource.title }}
                                </template>
                                <template #created_at>
                                    {{ resource.created_at }}
                                </template>
                                <template #description>
                                    {{  resource.description  }}
                                </template>
                            </ContentSection>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <SectionHeader :classes="'bg-[#002858]'" :section="'schools'">
            <template #header>
                <h3 class="text-white text-[36px] font-normal self-center section-header">
                    Latest School Profiles
                </h3>
            </template>
            <template #cta>
                <button class="bg-white px-4 py-2 rounded-sm border-2 border-[#002858] text-[#002858] text-[15px] font-medium cursor-pointer hover:text-[#0b1829] hover:border-2 hover:border-[#0b1829]">
                    View all schools
                </button>
            </template>
        </SectionHeader>

        <div class="px-[81px] py-20 h-[800px]">
            <div class="grid grid-cols-4 gap-[24px] w-full">
                <div v-for="school in this.schools" class="col-span-1 bg-white shadow-lg border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl">
                    <ContentSection>
                        <template #cover>
                            <div :class="`bg-[url('${school.cover}')]`" class="h-36 transition-all group-hover:h-0"></div>
                        </template>
                        <template #title>
                            {{ school.title }}
                        </template>
                        <template #created_at>
                            {{ school.created_at }}
                        </template>
                        <template #description>
                            {{  school.description  }}
                        </template>
                    </ContentSection>
                </div>
            </div>
        </div>

    </div>
</template>
