<script>
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

export default {

    components: {
        Swoosh,
        Circle,
        Gear,
        GearDottedLines,
        Shadow,
        RobotBody,
        RobotHead,
        LeftArm,
        RightArm
    },
    data() {
        return {
            welcomeMessage: 'Welcome to edSpark!',
            welcomeDescription: 'The edSpark Digital Hub is where education and technology meet. Discover how schools are already using technology effectively. Spark your imagination with advice on integrating tech into your classroom. Gain access to the best software and hardware at great prices. Join a community of like minded educators and partners.'
        }
    }
}
</script>

<template>
    <div class="bg-clip-content overflow-hidden">
        <div
            class="bg-clip-content bg-gradient-to-br from-[#002856] via-[#309A9A] to-[#B8E2DC] h-90 w-full"
        >
            <div
                class="-ml-24 md:ml-10 md:px-20 lg:-ml-5 xl:!px-20 2xl:!px-40 2xl:-ml-20 grid grid-cols-6 pb-[5rem] px-10"
            >
                <div class="col-span-6 pt-[120px]  md:!col-span-3">
                    <h1 class="font-semibold pb-8 text-3xl text-white md:!text-[2.5rem] lg:!text-lg">
                        {{ welcomeMessage }}
                    </h1>

                    <p class="font-normal text-white text-xl">
                        {{ welcomeDescription }}
                    </p>
                </div>
                <div
                    class="
                        hidden
                        relative
                        -top-36
                        left-40
                        welcomeRobot
                        z-10
                        md:!col-span-1
                        md:!scale-75
                        md:block
                        md:ml-auto
                        md:mr-40
                        lg:!ml-0
                        lg:!scale-70
                        "
                >
                    <!-- Gear and dots -->
                    <div class="">
                        <Gear class="absolute top-48 left-12 gear z-10" />
                        <GearDottedLines class="absolute top-48 left-12 dottedLines z-10" />
                    </div>
                    <!-- Shadow -->
                    <div class="">
                        <Shadow class="absolute top-[580px] left-64 robotShadow z-10" />
                    </div>
                    <!-- Robot Body -->
                    <div class="">
                        <RobotBody class="absolute top-[450px] left-64 robotBody z-20" />
                    </div>
                    <!-- Robot Head -->
                    <div class="">
                        <RobotHead class="absolute top-[250px] left-64 robotHead z-20" />
                    </div>
                    <!-- Robot Left Arm -->
                    <div class="">
                        <LeftArm class="absolute top-[390px] left-[410px] leftArm z-20" />
                    </div>
                    <!-- Robot Right Arm -->
                    <div class="">
                        <RightArm class="absolute top-[390px] rightArm left-40 z-20" />
                    </div>
                    <!-- Background circle -->
                    <div class="">
                        <Circle class="absolute top-52 left-20 z-0" />
                    </div>
                </div>
            </div>
        </div>
        <div class="!mt-0 h-[60px] xl:h-[130px] min-h-24 relative swooshContainer">
            <Swoosh style="transform: translate(0, -60%);" />
        </div>
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
