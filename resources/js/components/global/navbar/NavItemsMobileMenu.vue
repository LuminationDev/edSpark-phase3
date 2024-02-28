<script setup lang="ts">
import {onMounted, ref} from 'vue';

import ChevronRightNavIcon from "@/js/components/svg/ChevronRightNavIcon.vue";

const props = defineProps({
    route: {
        type: Object,
        required: true,
    },
    clickCallback: {
        type: Function,
        required: false,
        default: () => {
        },
    },
});

const hasChildren = ref(props.route.children && props.route.children.length > 0);
const navDropdownOpen = ref(false);

const handleDropdownToggle = () : void => {
    navDropdownOpen.value = !navDropdownOpen.value;
};
</script>

<template>
    <!--    if children exists, do not render router link, render a menu instead.-->
    <li
        v-if="hasChildren"
        class="
            cursor-pointer
            decoration-4
            decoration-[#B8E2DC]
            first-letter:uppercase
            flex
            justify-between
            items-center
            h-full
            mr-3
            py-4
            relative
            transition-all
            lg:!py-0
            "
        @click="clickCallback"
    >
        {{ route.meta.customText ?? route.name }}
        <ChevronRightNavIcon />

        <div
            v-if="hasChildren && navDropdownOpen"
            key="dropdown"
            class="absolute top-0 left-0 w-full z-[60]"
        >
            <li
                class="
                    -mt-6
                    hover:cursor-pointer
                    hover:fill-slate-200
                    hover:text-main-teal
                    cursor-pointer
                    fill-white
                    font-bold
                    h-8
                    ml-2
                    text-xl
                    w-8
                    "
                @click="handleDropdownToggle"
            />
            <div class="bg-main-navy font-light h-full ml-0">
                <NavItemsMobileMenu
                    v-for="(child, index) in props.route.children"
                    :key="index"
                    class="first-letter:uppercase text-xl text-white transition"
                    :to="{ name: child.name }"
                    :route="child"
                />
            </div>
        </div>
    </li>
    <!--     if it doesn't have children, renders a router link to route.        -->
    <li
        v-else-if="route.type === 'signout'"
    >
        <div
            class="mt-4 pb-4"
            @click="route.clickCallback"
        >
            {{ route.name ?? route.name }}
        </div>
    </li>
    <li
        v-else-if="route.type === 'signout'"
    >
        <div
            class="mt-4 pb-4"
            @click="route.clickCallback"
        >
            {{ route.name ?? route.name }}
        </div>
    </li>
    <li
        v-else
        @click="props.clickCallback"
    >
        <router-link
            :to="{name: route.name}"
            class="flex py-4 font-light text-xl"
        >
            {{ route.meta.customText ?? route.name }}
        </router-link>
    </li>
</template>
