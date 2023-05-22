<script setup>
    import { ref } from 'vue';

    const props = defineProps({
        route: {
            type: Object,
            required: true
        }
    });

    const navDropdownToggle = ref(false);
</script>

<template>
    <router-link
        :to="{ name: route.name }"
    >
        <li class="cursor-pointer uppercase hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
            {{ route.name }}
        </li>
    </router-link>

    <li
        v-if="route.meta.dropdownItems"
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
