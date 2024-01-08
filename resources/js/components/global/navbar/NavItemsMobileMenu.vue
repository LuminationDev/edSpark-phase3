<script setup>
import {required} from "@vuelidate/validators";
import {onMounted, ref} from 'vue';

import Close from "@/js/components/svg/Close.vue";

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
const isMobile = ref(false); // Add a variable to track mobile state

const handleDropdownToggle = () => {
    navDropdownOpen.value = !navDropdownOpen.value;
};


</script>

<template>
    <li
        v-if="hasChildren"
        class="
            cursor-pointer
            decoration-4
            decoration-[#B8E2DC]
            first-letter:uppercase
            flex
            items-center
            h-full
            ml-0
            py-4
            relative
            transition-all
            lg:!py-0
            "
        @click="handleDropdownToggle"
    >
        {{ route.meta.customText ?? route.name }}


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
                @click="handleToggleNavbar"
            >
                Back
            </li>
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

    <li
        v-else
        @click="props.clickCallback"
    >
        <router-link
            :to="{ name: route.name }"
            class="flex p-4"
        >
            {{ route.meta.customText ?? route.name }}
        </router-link>
    </li>
</template>
