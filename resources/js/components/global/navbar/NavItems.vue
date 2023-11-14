<script setup>
import {ref} from 'vue';

const props = defineProps({
    route: {
        type: Object,
        required: true
    },
    clickCallback:{
        type: Function,
        required: false,
        default: () => {}
    }
});

const navDropdownToggle = ref(false);
</script>

<template>
    <li
        class="cursor-pointer decoration-4 decoration-[#B8E2DC] py-4 transition-all underline-offset-8 hover:underline first-letter:uppercase lg:!py-0"
        @click="props.clickCallback"
    >
        <router-link
            :to="{ name: route.name }"
        >
            {{ route.meta.customText ?? route.name }}
        </router-link>
    </li>

    <li
        v-if="route.meta.dropdownItems"
        class="cursor-pointer relative"
    >
        <div
            class="h-fit"
            @mouseover="navDropdownToggle = true"
            @mouseleave="navDropdownToggle = false"
        >
            Technology
            <div
                v-show="navDropdownToggle"
                class="absolute navDropdown"
                @mouseover="navDropdownToggle = true"
            >
                <div class="bg-[#002856]/50 mt-[8px]">
                    <ul class="flex flex-col font-semibold gap-4 py-4 text-[24px] text-center text-white">
                        <router-link
                            class="flex"
                            to="/software"
                        >
                            <li
                                class="
                                    cursor-pointer
                                    decoration-4
                                    decoration-[#B8E2DC]
                                    mx-auto
                                    px-4
                                    transition-all
                                    underline-offset-8
                                    hover:underline
                                    "
                            >
                                Software
                            </li>
                        </router-link>
                        <router-link to="/hardware">
                            <li
                                class="
                                    cursor-pointer
                                    decoration-4
                                    decoration-[#B8E2DC]
                                    mx-auto
                                    px-4
                                    transition-all
                                    underline-offset-8
                                    hover:underline
                                    "
                            >
                                Hardware
                            </li>
                        </router-link>
                    </ul>
                </div>
            </div>
        </div>
    </li>

    <!-- <li
        v-if="route.meta.dropdownItem"
        class="relative cursor-pointer"
    >
        <div
            class="h-fit"
            @mouseover="navDropdownToggle = true"
            @mouseleave="navDropdownToggle = false"
        >
            Technology
            <div
                v-show="navDropdownToggle"
                class="navDropdown absolute  "
                @mouseover="navDropdownToggle = true"
            >
                <div class="bg-[#002856]/50 mt-[8px]">
                    <ul class="flex flex-col gap-4 py-4 text-white text-center text-[24px] font-semibold font-['Poppins']">
                        <router-link
                            class="flex"
                            to="/software"
                        >
                            <li class="px-4 mx-auto cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                                Software
                            </li>
                        </router-link>
                        <router-link to="/hardware">
                            <li class="px-4 mx-auto cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                                Hardware
                            </li>
                        </router-link>
                    </ul>
                </div>
            </div>
        </div>
    </li> -->
</template>
