<script setup>
import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import {LandingHeroText} from "@/js/constants/LandingPageTitle";

import Swoosh from '../svg/DashboardSwoosh.vue';
import {
    Circle,
    Gear,
    GearDottedLines,
    LeftArm,
    RightArm,
    RobotBody,
    RobotHead,
    Shadow
} from '../svg/welcomeRobot/index.js'

</script>

<template>
    <BaseLandingHero
        :title="LandingHeroText['dashboard']['title']"
        :title-paragraph="LandingHeroText['dashboard']['subtitle']"
        background-color="navy"
    >
        <template #robotIllustration>
            <div class="welcomeRobot">
                <!-- Gear and dots -->
                <div class="">
                    <Gear class="absolute top-8 left-12 gear z-10" />
                    <GearDottedLines class="absolute top-8 left-12 dottedLines z-10" />
                </div>
                <!-- Shadow -->
                <div class="">
                    <Shadow class="absolute top-[420px] left-64 robotShadow z-10" />
                </div>
                <!-- Robot Body -->
                <div class="">
                    <RobotBody class="absolute top-[250px] left-64 robotBody z-20" />
                </div>
                <!-- Robot Head -->
                <div class="">
                    <RobotHead class="absolute top-[50px] left-64 robotHead z-20" />
                </div>
                <!-- Robot Left Arm -->
                <div class="">
                    <LeftArm class="absolute top-[190px] left-[410px] leftArm z-20" />
                </div>
                <!-- Robot Right Arm -->
                <div class="">
                    <RightArm class="absolute top-[190px] rightArm left-40 z-20" />
                </div>
                <!-- Background circle -->
                <div class="">
                    <Circle class="absolute top-10 left-20 z-0" />
                </div>
            </div>
        </template>
    </BaseLandingHero>
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
        top: 250px;
    }
    to {
        top: 235px;
        transform: scaleX(0.9);
        /* transform-origin: bottom;  */
    }
}

@keyframes excitedHead {
    from {
        top: 50px;
        transform: rotate(0deg);
    }
    to {
        top: 35px;
        transform: rotate(3deg);
        /* transform: translateY(1.1);
        transform-origin: bottom; */
    }
}

@keyframes leftArm {
    from {
        transform: rotate(0deg);
        top: 190px;
        left: 410px;
    }
    to {
        top: 170px;
        left: 395px;
        transform: rotate(-32deg);
        transform-origin: bottom left 25%;
    }
}

@keyframes rightArm {
    from {
        transform: rotate(0deg);
        top: 190px;
        left: 10rem;
    }
    to {
        top: 170px;
        left: 11rem;
        transform: rotate(36deg);
        transform-origin: bottom left 25%;
    }
}
</style>
