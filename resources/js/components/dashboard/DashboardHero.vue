<script>
    import Swoosh from '../svg/DashboardSwoosh.vue';
    // import WelcomeRobot from '../svg/WelcomeRobot.vue';
    import {
        Circle,
        Gear,
        GearDottedLines
    } from '../svg/welcomeRobot/index.js'

    export default {
        data() {
            return {
                welcomeMessage: 'Welcome to edSpark!',
                welcomeDescription: 'The edSpark Digital Hub is where education and technology meet. Discover how schools are already using technology effectively. Spark your imagination with advice on integrating tech into your classroom. Gain access to the best software and hardware at great prices. Join a community of like minded educators and partners with forums and events.'
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
        <div class="bg-clip-content w-full h-[585px] bg-gradient-to-br from-[#002856] via-[#309A9A] to-[#B8E2DC]">
            <div class="grid grid-cols-6 px-40">
                <div class="col-span-3">
                    <h1 class="pt-[240px] pb-8 text-white text-[48px] font-semibold">
                        {{ this.welcomeMessage }}
                    </h1>

                    <p class="text-white font-normal text-[18px]">
                        {{ this.welcomeDescription }}
                    </p>
                </div>
                <div class="welcomeRobot col-span-3 relative">
                    <!-- Gear and dots -->
                    <div class="">
                        <Gear class="z-10 gear absolute top-48 left-12"/>
                        <GearDottedLines class="z-10 dottedLines absolute top-48 left-12" />
                    </div>
                    <!-- Background circle -->
                    <div class="">
                        <Circle class="z-0 absolute top-52 left-20" />
                    </div>




                    <!-- <WelcomeRobot  class="absolute -bottom-[150px] right-0 mx-auto"/> -->
                </div>
            </div>
        </div>
        <Swoosh class="-mt-[100px]" />
    </div>
</template>

<style>
    .welcomeRobot:hover .gear {
        animation: rotateClockwise 5s linear infinite;
        transform: rotate(0deg);
        transition: transform 5s linear;
    }

    .welcomeRobot:hover .dottedLines {
        animation: rotateAntiClockwise 12s linear infinite;
    }

    @keyframes rotateClockwise {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
            transform-origin: center;
        }
    }

    @keyframes rotateAntiClockwise {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(-360deg);
            transform-origin: center;
        }
    }
</style>
