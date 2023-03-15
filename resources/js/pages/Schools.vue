<script setup>

import SchoolsHero from '../components/schools/SchoolsHero.vue';
import SearchableMap from '../components/schools/SearchableMap.vue';
import SchoolCard from "@/js/components/schools/SchoolCard.vue";

const schoolTempData  = [
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
                name: 'Microsoft Teams',
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
                name: 'Microsoft Teams',
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
                name: 'Microsoft Teams',
                description: 'is a proprietary business communication platform developed by Microsoft, as part of the Microsoft 365 family of products.',
                category: 'Platforms',
            },
        ]
    }
]
</script>
<template>
    <div>
        <SchoolsHero />
        <div class="px-[81px] py-20">
            <div class="grid grid-cols-4 gap-[24px] w-full">
                <div
                    v-for="(school,index) in schoolTempData"
                    :key="index"
                    class="col-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl"
                >
                    <SchoolCard :school-data="school" />
                </div>
            </div>
        </div>

        <div class="px-[81px] py-20">
            <SearchableMap />
        </div>
    </div>
</template>
