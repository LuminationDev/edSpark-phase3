<script setup>
import {required} from "@vuelidate/validators";
import {ref} from 'vue';

import NavbarMobileMenu from "@/js/components/global/navbar/NavbarMobileMenu.vue";

const props = defineProps({
    route: {
        type: Object,
        required: true
    },
    clickCallback: {
        type: Function,
        required: false,
        default: () => {
        }
    }


});

const hasChildren = ref(false)
if (props.route.children && props.route.children.length > 0) {
    console.log('route is' + props.route.name)
    console.log('has children')
    console.log(props.route)
    hasChildren.value = true
}
const navDropdownToggle = ref(false);


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
        @click="props.clickCallback"
    >
        <router-link
            :to="{ name: route.name }"
            class=""
        >
            {{ route.meta.customText ?? route.name }}
        </router-link>
        <div
            v-if="hasChildren"
            class="absolute bottom-0 decoration-4 h-full overlayForTriggerDropdown w-full"
            @mouseover="navDropdownToggle = true"
            @mouseleave="navDropdownToggle = false"
        >
            <div
                v-show="navDropdownToggle"
                class="absolute top-16 dropdownBackgroundContainer"
            >
                <div
                    class="-ml-2 bg-[#F5F5F5] font-medium h-full p-0 w-60"
                >
                    <NavItems
                        v-for="(child, index) in props.route.children"
                        :key="index"
                        class="first-letter:uppercase text-black text-sm  hover:bg-[#E7ECEE] hover:font-bold"
                        :to="{ name: child.name }"
                        :route="child"
                    />
                </div>
            </div>
        </div>
    </li>
    <li
        v-else
        class=""
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
<style>
/* Existing styles */

@media screen and (max-width: 768px) {
    .navDropdownToggle:hover {
        display: none;
    }
}
</style>

<!--  <li-->
<!--      v-if="hasChildren"-->
<!--      class="-ml-4 lg:!py-0 cursor-pointer decoration-4 decoration-[#B8E2DC] first-letter:uppercase py-4 relative transition-all"-->
<!--      @click="props.clickCallback"-->
<!--  >-->
<!--    <router-link-->
<!--        :to="{ name: route.name }"-->
<!--        class="flex items-center"-->
<!--    >-->
<!--      &lt;!&ndash; Wrap the entire content in the router-link &ndash;&gt;-->
<!--      {{ route.meta.customText ?? route.name }}-->
<!--      <div-->
<!--          v-if="hasChildren"-->
<!--          class="absolute bottom-0 decoration-4 h-full w-full"-->
<!--          @mouseover="navDropdownToggle = true"-->
<!--          @mouseleave="navDropdownToggle = false"-->
<!--      >-->
<!--        &lt;!&ndash; Nested content &ndash;&gt;-->
<!--        <div-->
<!--            v-show="navDropdownToggle"-->
<!--            class="absolute m-2 navDropdown"-->
<!--        >-->
<!--          <div-->
<!--              class="bg-amber-50 font-sans h-full mt-8 p-0 w-60"-->
<!--          >-->
<!--                        <span><NavItems-->
<!--                            v-for="(child, index) in props.route.children"-->
<!--                            :key="index"-->
<!--                            class="-ml-0 hover:!bg-main-darkTeal cursor-pointer flex h-16 p-3 text-black transition-all"-->
<!--                            :route="child"-->
<!--                        /></span>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </router-link>-->
<!--  </li>-->
<!--  <li-->
<!--      v-else-->
<!--      class=""-->
<!--      @click="props.clickCallback"-->
<!--      @mouseover="navDropdownToggle = true"-->
<!--  >-->
<!--    <router-link-->
<!--        :to="{ name: route.name }"-->
<!--        class="flex items-center"-->
<!--    >-->
<!--      &lt;!&ndash; Wrap the entire content in the router-link &ndash;&gt;-->
<!--      {{ route.meta.customText ?? route.name }}-->
<!--      <div-->
<!--          v-if="hasChildren"-->
<!--          class="absolute bottom-0 decoration-4 h-full w-full"-->
<!--          @mouseover="navDropdownToggle = true"-->
<!--          @mouseleave="navDropdownToggle = false"-->
<!--      >-->
<!--        &lt;!&ndash; Nested content &ndash;&gt;-->
<!--        <div-->
<!--            v-show="navDropdownToggle"-->
<!--            class="absolute m-2 navDropdown"-->
<!--        >-->
<!--          <div-->
<!--              class="bg-amber-50 font-sans h-full mt-8 p-0 w-60"-->
<!--          >-->
<!--                        <span><NavItems-->
<!--                            v-for="(child, index) in props.route.children"-->
<!--                            :key="index"-->
<!--                            class="-ml-0 hover:!bg-main-darkTeal cursor-pointer flex h-16 p-3 text-black transition-all"-->
<!--                            :route="child"-->
<!--                        /></span>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </router-link>-->
<!--  </li>-->


<!--    <li-->
<!--        v-if="route.meta.dropdownItems"-->
<!--        class="cursor-pointer relative"-->
<!--    >-->
<!--        <div-->
<!--            class="h-fit"-->
<!--            @mouseover="navDropdownToggle = true"-->
<!--            @mouseleave="navDropdownToggle = false"-->
<!--        >-->
<!--            School-->
<!--            <div-->
<!--                v-show="navDropdownToggle"-->
<!--                class="absolute navDropdown"-->
<!--                @mouseover="navDropdownToggle = true"-->
<!--            >-->
<!--                <div class="bg-[#002856]/50 mt-[8px]">-->
<!--                    <ul class="flex flex-col font-semibold gap-4 py-4 text-[24px] text-center text-white">-->
<!--                        <router-link-->
<!--                            class="flex"-->
<!--                            to="/software"-->
<!--                        >-->
<!--                            <li-->
<!--                                class="-->
<!--                                    cursor-pointer-->
<!--                                    decoration-4-->
<!--                                    decoration-[#B8E2DC]-->
<!--                                    mx-auto-->
<!--                                    px-4-->
<!--                                    transition-all-->
<!--                                    underline-offset-8-->
<!--                                    hover:underline-->
<!--                                    "-->
<!--                            >-->
<!--                                Software-->
<!--                            </li>-->
<!--                        </router-link>-->
<!--                        <router-link to="/hardware">-->
<!--                            <li-->
<!--                                class="-->
<!--                                    cursor-pointer-->
<!--                                    decoration-4-->
<!--                                    decoration-[#B8E2DC]-->
<!--                                    mx-auto-->
<!--                                    px-4-->
<!--                                    transition-all-->
<!--                                    underline-offset-8-->
<!--                                    hover:underline-->
<!--                                    "-->
<!--                            >-->
<!--                                Hardware-->
<!--                            </li>-->
<!--                        </router-link>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </li>-->

<!--    <li-->
<!--        v-else-if="route.meta.dropdownSc"-->
<!--        class="cursor-pointer relative"-->
<!--    >-->
<!--        <div-->
<!--            class="h-fit"-->
<!--            @mouseover="navDropdownToggle = true"-->
<!--            @mouseleave="navDropdownToggle = false"-->
<!--        >-->
<!--            School-->
<!--            <div-->
<!--                v-show="navDropdownToggle"-->
<!--                class="absolute navDropdown"-->
<!--                @mouseover="navDropdownToggle = true"-->
<!--            >-->
<!--                <div class="bg-[#002856]/50 mt-[8px]">-->
<!--                    <ul class="flex flex-col font-semibold gap-4 py-4 text-[24px] text-center text-white">-->
<!--                        <router-link-->
<!--                            class="flex"-->
<!--                            to="/software"-->
<!--                        >-->
<!--                            <li-->
<!--                                class="-->
<!--                                    cursor-pointer-->
<!--                                    decoration-4-->
<!--                                    decoration-[#B8E2DC]-->
<!--                                    mx-auto-->
<!--                                    px-4-->
<!--                                    transition-all-->
<!--                                    underline-offset-8-->
<!--                                    hover:underline-->
<!--                                    "-->
<!--                            >-->
<!--                                Software-->
<!--                            </li>-->
<!--                        </router-link>-->
<!--                        <router-link to="/hardware">-->
<!--                            <li-->
<!--                                class="-->
<!--                                    cursor-pointer-->
<!--                                    decoration-4-->
<!--                                    decoration-[#B8E2DC]-->
<!--                                    mx-auto-->
<!--                                    px-4-->
<!--                                    transition-all-->
<!--                                    underline-offset-8-->
<!--                                    hover:underline-->
<!--                                    "-->
<!--                            >-->
<!--                                Hardware-->
<!--                            </li>-->
<!--                        </router-link>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </li>-->


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

