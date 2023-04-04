<script>
    import Swoosh from '../svg/DashboardSwoosh.vue';
    // import WelcomeRobot from '../svg/WelcomeRobot.vue';
    import {
        Circle,
        Gear,
        GearDottedLines,
        Shadow,
        RobotBody,
        RobotHead,
        LeftArm,
        RightArm
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
            GearDottedLines,
            Shadow,
            RobotBody,
            RobotHead,
            LeftArm,
            RightArm
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
                    <!-- Shadow -->
                    <div class="">
                        <Shadow class="z-10 robotShadow absolute top-[620px] left-64" />
                    </div>
                    <!-- Robot Body -->
                    <div class="">
                        <RobotBody class="z-20 robotBody absolute top-[450px] left-64" />
                    </div>
                    <!-- Robot Head -->
                    <div class="">
                        <RobotHead class="robotHead z-20 absolute top-[250px] left-64" />
                    </div>
                    <!-- Robot Left Arm -->
                    <div class="">
                        <LeftArm class="leftArm z-20 absolute top-[390px] left-[410px]"/>
                    </div>
                    <!-- Robot Right Arm -->
                    <div class="">
                        <RightArm class="rightArm z-20 absolute top-[390px] left-40" />
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
    /* .welcomeRobot .gear {
        animation: rotateClockwise infinite forwards;
        transform: rotate(0deg);
        transition: transform 5s linear;
    } */

    .welcomeRobot:hover .gear {
        animation: rotateClockwise 12s forwards;
        transform: rotate(0deg);
        transition: transform 5s linear;
    }

    .welcomeRobot:hover .dottedLines {
        animation: rotateAntiClockwise 12s linear forwards;
    }

    .welcomeRobot:hover .robotShadow {
        animation: shadowStretch 0.3s forwards;
        animation-iteration-count: 1;
    }

    .welcomeRobot:hover .robotBody {
        animation: veryExcite 0.2s linear forwards;
        /* animation-iteration-count: 1; */
    }

    .welcomeRobot:hover .robotHead {
        animation: excitedHead 0.25s linear forwards;
        /* animation-iteration-count: 1; */
    }

    .welcomeRobot:hover .leftArm {
        animation: leftArm 0.25s linear forwards;
        /* animation-iteration-count: 1; */
    }

    .welcomeRobot:hover .rightArm {
        animation: rightArm 0.25s linear forwards;
        /* animation-iteration-count: 1; */
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

    @keyframes shadowStretch {
        from {
            transform: scaleX(1);
        }
        to {
            transform: scaleX(0.8);
            transform-origin: center;
        }
    }

    /* Something is very wrong here... */
    @keyframes veryExcite {
        from {
            transform: scaleX(1);
            top: 450px;
        }
        to {
            top: 435px;
            transform: scaleX(0.9);
            /* transform-origin: bottom;  */
        }
    }

    @keyframes excitedHead {
        from {
            top: 250px;
            transform: rotate(0deg);
        }
        to {
            top: 235px;
            transform: rotate(3deg);
            /* transform: translateY(1.1);
            transform-origin: bottom; */
        }
    }

    @keyframes leftArm {
        from {
            transform: rotate(0deg);
            top: 390px;
            left: 410px;
        }
        to {
            top: 370px;
            left: 395px;
            transform: rotate(-32deg);
            transform-origin: bottom left 25%;
        }
    }

    @keyframes rightArm {
        from {
            transform: rotate(0deg);
            top: 390px;
            left: 10rem;
        }
        to {
            top: 370px;
            left: 11rem;
            transform: rotate(36deg);
            transform-origin: bottom left 25%;
        }
    }
</style>
