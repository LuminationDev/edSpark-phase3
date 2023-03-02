<script>
    import { RouterLink } from 'vue-router';
    import NavSwoosh from '../svg/NavSwoosh.vue';
    import Logo from '../svg/Logo.vue';

    export default {


        components: {
            NavSwoosh,
            Logo
        }
    }
</script>

<template>
    <div class="w-full h-[240px] relative">
        <div class="nav-background w-full h-full pt-7 bg-[url(http://localhost:5173/resources/assets/images/children-vr.png)] bg-no-repeat bg-cover">
            <nav class="bg-[#002856]/50 py-2 px-12 w-full">
                <ul class="flex flex-wrap gap-8 text-white text-[24px] font-semibold font-['Poppins']">
                    <RouterLink to="/dashboard">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Dash
                        </li>
                    </RouterLink>

                    <RouterLink to="/schools">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Schools
                        </li>
                    </RouterLink>

                    <RouterLink to="/advice">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Advice
                        </li>
                    </RouterLink>

                    <!-- <RouterLink> -->
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Technology
                        </li>
                    <!-- </RouterLink> -->

                    <RouterLink to="/community">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Community
                        </li>
                    </RouterLink>

                    <RouterLink to="/partners">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Partners
                        </li>
                    </RouterLink>

                    <RouterLink to="/events">
                        <li class="cursor-pointer hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">
                            Events
                        </li>
                    </RouterLink>

                </ul>
            </nav>

        </div>
        <Logo class="absolute right-20 top-4 z-40"/>
        <NavSwoosh class="w-full absolute -bottom-6 left-0 right-0 pointer-events-none" />
    </div>




</template>

<style>
    .nav-background {
        clip-path: inset(0 0 round 0 0 75%);
    }
</style>
