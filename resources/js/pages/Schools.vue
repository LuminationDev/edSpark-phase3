<script>
    /**
     * Import Components
     */
    import SchoolsHero from '../components/schools/SchoolsHero.vue';
    import ContentSection from '../components/global/ContentSection.vue';
    import SchoolsTech from '../components/schools/SchoolsTech.vue';
    import SearchableMap from '../components/schools/SearchableMap.vue';

    /**
     * Import SVG's
     */
    import Microsoft from '../components/svg/Microsoft.vue';
    import ThreeDPrintingIcon from '../components/svg/ThreeDPrintingIcon.vue';
    import AppleIcon from '../components/svg/AppleIcon.vue';
    import FrogIcon from '../components/svg/FrogIcon.vue';
    import IoTIcon from '../components/svg/IoTIcon.vue';
    import RoboticsIcon from '../components/svg/RoboticsIcon.vue';
    import ARIcon from '../components/svg/ARIcon.vue';
    import VRIcon from '../components/svg/VRIcon.vue';

    export default {
        components: {
            SchoolsHero,
            ContentSection,
            Microsoft,
            ThreeDPrintingIcon,
            AppleIcon,
            FrogIcon,
            IoTIcon,
            RoboticsIcon,
            ARIcon,
            VRIcon,
            SchoolsTech,
            SearchableMap
        },

        data() {
            return {
                schools: [
                    {
                        title: 'Adelaide High School',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Quisque quis luctus turpis. Nam et arcu facilisis, blandit felis ut, egestas dolor. Cras at dignissim augue. Curabitur placerat fermentum mollis. Vestibulum mollis facilisis placerat.',
                        created_at: '25th Feb 2023',
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
                                description: 'or additive manufacturing is the construction of a three-dimensional object from a CAD model or a digital 3D model.',
                                category: 'Emerging tech',
                            },
                            {
                                name: 'Mircosoft Teams',
                                description: 'is a proprietary business communication platform developed by Microsoft, as part of the Microsoft 365 family of products.',
                                category: 'Platforms',
                            },
                        ]
                    },
                    {
                        title: 'East Adelaide School',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus.',
                        created_at: '14th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                        techUsed: [
                            {
                                name: 'Mircosoft Teams',
                                description: 'is a proprietary business communication platform developed by Microsoft, as part of the Microsoft 365 family of products.',
                                category: 'Platforms',
                            },
                            {
                                name: 'Robotics',
                                description: 'is an interdisciplinary branch of computer science and engineering. Robotics involves design, construction, operation, and use of robots. ',
                                category: 'Emerging tech',
                            },
                            {
                                name: 'Frog',
                                description: 'provides whole school systems for teaching, learning and communication. Additionally we create platforms that allow businesses, MATs and schools to create and deliver online training',
                                category: 'Platforms'
                            },
                            {
                                name: 'IoT',
                                description: '(or "Internet of Things") describes physical objects with sensors, processing ability, software, and other technologies that connect and exchange data with other devices and systems over the Internet or other communications networks.',
                                category: 'Emerging tech',
                            },
                        ]
                    },
                    {
                        title: 'North Adelaide Primary School',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Nam et arcu facilisis, blandit felis ut, egestas dolor.',
                        created_at: '29th Jan 2023',
                        cover: 'https://picsum.photos/200/300',
                        techUsed: [
                            {
                                name: 'Apple',
                                description: 'technology and resources empower every kind of educator — and every kind of student — to learn, create and define their own success.',
                                category: 'Platforms'
                            },
                            {
                                name: 'Frog',
                                description: 'provides whole school systems for teaching, learning and communication. Additionally we create platforms that allow businesses, MATs and schools to create and deliver online training',
                                category: 'Platforms'
                            },
                            {
                                name: 'VR',
                                description: 'is a simulated experience that employs pose tracking and 3D near-eye displays to give the user an immersive feel of a virtual world.',
                                category: 'Emerging tech',
                            },
                            {
                                name: 'IoT',
                                description: '(or "Internet of Things") describes physical objects with sensors, processing ability, software, and other technologies that connect and exchange data with other devices and systems over the Internet or other communications networks.',
                                category: 'Emerging tech',
                            },
                        ]
                    },
                    {
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
                                description: 'or additive manufacturing is the construction of a three-dimensional object from a CAD model or a digital 3D model.',
                                category: 'Emerging tech',
                            },
                            {
                                name: 'Mircosoft Teams',
                                description: 'is a proprietary business communication platform developed by Microsoft, as part of the Microsoft 365 family of products.',
                                category: 'Platforms',
                            },
                        ]
                    }
                ],
            }
        }
    }
</script>

<template>
    <div>
        <SchoolsHero />

        <div class="px-[81px] py-20">
            <div class="grid grid-cols-4 gap-[24px] w-full">
                <div v-for="school in this.schools" class="col-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl">
                    <router-link :to="`/schools/${school.title}`">
                        <ContentSection>
                            <template #cover>
                                <div :class="`bg-[url('${school.cover}')]`" class="h-36 group-hover:h-0 transition-all"></div>
                            </template>
                            <template #title>
                                {{ school.title }}
                            </template>
                            <template #techUsed>
                                <p class="pt-6 text-black text-[18px] font-medium">Tech used:</p>
                                <div class=" pt-4 flex flex-row w-full justify-between place-items-center ">
                                    <div class="flex" v-for="tech in school.techUsed">
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'Mircosoft Teams'"><Microsoft /><SchoolsTech :tech="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === '3D Printing'"><ThreeDPrintingIcon /><SchoolsTech :tech="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'Apple'"><AppleIcon /><SchoolsTech :tech="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'Frog'"><FrogIcon /><SchoolsTech :tech="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'IoT'"><IoTIcon /><SchoolsTech :tech="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'Robotics'"><RoboticsIcon /><SchoolsTech :tech="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'AR'"><ARIcon /><SchoolsTech :tech="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'VR'"><VRIcon /><SchoolsTech :tech="tech"/></div>
                                    </div>
                                </div>
                            </template>
                        </ContentSection>
                    </router-link>
                </div>
            </div>
        </div>

        <div class="px-[81px] py-20">
            <SearchableMap />
        </div>
    </div>
</template>
