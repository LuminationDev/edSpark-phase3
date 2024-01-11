<script setup>
import {onMounted, ref} from 'vue';

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

const handleDropdownToggle = () => {
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
        <svg
            class="mt-1"
            width="30"
            height="30"
            viewBox="0 0 20 23"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M17.0031 11.2999C17.0037 11.7156 16.8141 12.1113 16.4828 12.386L9.04349 18.549C8.53078 18.9541 7.76838 18.8997 7.32635 18.4264C6.88431 17.9532 6.92433 17.2342 7.41647 16.807L13.9219 11.418C13.958 11.3882 13.9787 11.3452 13.9787 11.2999C13.9787 11.2547 13.958 11.2116 13.9219 11.1818L7.41647 5.79279C6.92433 5.36564 6.88431 4.64665 7.32635 4.1734C7.76838 3.70016 8.53078 3.64576 9.04349 4.05088L16.4801 10.2119C16.8122 10.4871 17.0027 10.8834 17.0031 11.2999Z"
                fill="white"
            />
        </svg>
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
                    text-2xl
                    w-8
                    "
                @click="handleDropdownToggle"
            />
            <div class="bg-main-navy font-medium h-full ml-0">
                <NavItemsMobileMenu
                    v-for="(child, index) in props.route.children"
                    :key="index"
                    class="first-letter:uppercase text-2xl text-white transition"
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
        v-else
        @click="props.clickCallback"
    >
        <router-link
            :to="route.path ? {path:route.path} : { name: route.name }"
            class="flex py-4"
        >
            {{ route.meta.customText ?? route.name }}
        </router-link>
    </li>
</template>
