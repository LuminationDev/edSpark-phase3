<script>
    import Swoosh from '../svg/CommunitySwoosh.vue';
    // import WelcomeRobot from '../svg/WelcomeRobot.vue';
    import {
        Circle,
        Gear,
        GearDottedLines
    } from '../svg/welcomeRobot/index.js'

    export default {
        data() {
            return {
                welcomeMessage: 'Community Forum',
                welcomeDescription: 'Make connections, ask questions and build your collaborative community. Find answers from other teachers and technology experts.'
            }
        },

        components: {
            Swoosh,
            // WelcomeRobot
            Circle,
            Gear,
            GearDottedLines
        }
    }
</script>

<template>
    <div class="bg-clip-content">
        <div class="bg-clip-content w-full h-[545px] bg-gradient-to-r from-[#002856] via-[#309A9A] to-[#B8E2DC]">
            <div class="grid grid-cols-6 px-40">
                <div class="col-span-3">
                    <h1 class="pt-[240px] pb-8 text-white text-[36px] font-semibold">
                        {{ this.welcomeMessage }}
                    </h1>

                    <p class="text-white font-normal text-[18px]">
                        {{ this.welcomeDescription }}
                    </p>
                </div>
                <div class="welcomeRobot col-span-3 relative">
                    <img class="mt-48 mx-auto" src="../../../assets/images/CommunityIllustration.png" alt="Community people asking questions">




                    <!-- <WelcomeRobot  class="absolute -bottom-[150px] right-0 mx-auto"/> -->
                </div>
            </div>
        </div>
        <Swoosh class="-mt-[100px]" />
    </div>
</template>
