<script>
/**
 * IMPORT DEPENDENCIES
 */
import sanitizeHtml from 'sanitize-html';
import { computed, ref, getCurrentInstance } from 'vue';

/**
 * IMPORT COMPONENTS
 */
import DashboardHero from '../components/dashboard/DashboardHero.vue';
import SectionHeader from '../components/global/SectionHeader.vue';
import ContentSection from '../components/global/ContentSection.vue';
import SchoolsTech from '../components/schools/SchoolsTech.vue';
import ConfirmInfo from '../components/dashboard/ConfirmInfo.vue';
import SearchDropdown from 'search-dropdown-vue';

/**
 * IMPORT SVGS
 */
import InPerson from '../components/svg/InPerson.vue';
import Virtual from '../components/svg/Virtual.vue';
import DepartmentApproved from '../components/svg/DepartmentApproved.vue';
import DepartmentProvided from '../components/svg/DepartmentProvided.vue';
import NegotiatedDeals from '../components/svg/NegotiatedDeals.vue';
import Microsoft from '../components/svg/Microsoft.vue';
import ThreeDPrintingIcon from '../components/svg/ThreeDPrintingIcon.vue';
import AppleIcon from '../components/svg/AppleIcon.vue';
import FrogIcon from '../components/svg/FrogIcon.vue';
import IoTIcon from '../components/svg/IoTIcon.vue';
import RoboticsIcon from '../components/svg/RoboticsIcon.vue';
import ARIcon from '../components/svg/ARIcon.vue';
import VRIcon from '../components/svg/VRIcon.vue';
import SoftwareRobot from '../components/svg/SoftwareRobot.vue';

/**
 * IMPORT STORES
 */
import { useAdviceStore } from '../stores/useAdviceStore.js';
import { useSoftwareStore } from '../stores/useSoftwareStore.js';
import { useUserStore } from '../stores/useUserStore';
import { useSchoolsStore } from '../stores/useSchoolsStore.js';
import { useSiteStore } from '../stores/useSiteStore.js';
import { useEventsStore } from '../stores/useEventsStore.js';
import { useRolesStore } from '../stores/useRolesStore.js';

/**
 * TESTING
 */
import oktaAuth from '../constants/oktaAuth.js';
// import { JwtVerifier } from '@okta/jwt-verifier';
// import { useOktaAuth } from '@okta/okta-vue';



export default {
    components: {
        DashboardHero,
        SectionHeader,
        ContentSection,
        SchoolsTech,
        InPerson,
        Virtual,
        DepartmentApproved,
        DepartmentProvided,
        NegotiatedDeals,
        Microsoft,
        ThreeDPrintingIcon,
        AppleIcon,
        FrogIcon,
        IoTIcon,
        RoboticsIcon,
        ARIcon,
        VRIcon,
        SoftwareRobot,
        ConfirmInfo,
        SearchDropdown
    },

    setup() {
        const adviceStore = useAdviceStore();
        const softwareStore = useSoftwareStore();
        const userStore = useUserStore();
        const schoolsStore = useSchoolsStore();
        const siteStore = useSiteStore();
        const eventsStore = useEventsStore();
        const roleStore = useRolesStore();

        siteStore.loadSites();

        adviceStore.loadDashboardResources();
        softwareStore.loadArticles();
        // userStore.loadCurrentUser();
        schoolsStore.loadSchools();
        eventsStore.loadEvents();
        roleStore.loadRoles();

        const adviceResources = computed(() => {
            return adviceStore.getResources;
        });

        const softwareArticles = computed(() => {
            return softwareStore.getArticles;
        });

        const schools = computed(() => {
            return schoolsStore.getSchools;
        });

        const events = computed(() => {
            return eventsStore.getEvents;
        });

        const allSites = computed(() => {
            const theSites = siteStore.getSites;
            const siteArr = [];
            theSites.forEach(site => {
                if (site.category_code === 'SCHL' || site.category_code === 'PRESC') {
                    siteArr.push({ id: site.id, name: site.site_name });
                }
            });
            console.log(siteArr);
            if (siteArr.length > 0) {
                return siteArr;
            }
        });

        /**
         * Temporary - working on roles controller
         */
        const allRoles = computed(() => {
            return [
                {
                    id: 'SCHLDR',
                    name: 'School Principal',
                },
                {
                    id: 'PRESCLDR',
                    name: 'Preschool Director',
                },
                {
                    id: 'SITELDR',
                    name: 'Site Leadership Team',
                },
                {
                    id: 'STCH',
                    name: 'School Teacher',
                },
                {
                    id: 'PTCH',
                    name: 'Preschool Teacher',
                },
                {
                    id: 'SITESUPP',
                    name: 'Site Support Staff',
                },
                {
                    id: 'PSACT',
                    name: 'Public Sector Act',
                },
                {
                    id: 'IT',
                    name: 'Staff with IT admin responsibilities',
                },

            ]
        });

        const cardHoverToggle = ref(false);

        /**
         * Change this to TRUE to simulate the First Login Experience
         */
        const isFirstVisit = ref(false);

        const createNewUser = async (data) => {
            // Get the site according to the ID
            const sites = siteStore.getSites;
            const siteData = sites.find(site => site.id === data.site.id);
            data.site = siteData;
            console.log(data);
            const result = await userStore.createUser(data);
            return result;
        };

        const imageURL = import.meta.env.VITE_SERVER_IMAGE_API

        return {
            // email,

            createNewUser,
            userStore,
            adviceStore,
            softwareStore,
            adviceResources,
            softwareArticles,
            cardHoverToggle,
            isFirstVisit,
            imageURL,
            schools,
            allSites,
            events,
            allRoles
        }
    },

    data() {
        return {
            steps:[
                {'step_no':1,'step_valid':false,'step_skip':true},
                {'step_no':2,'step_valid':false,'step_skip':true},
                {'step_no':3,'step_valid':false,'step_skip':true},
                {'step_no':4,'step_valid':false,'step_skip':true},
            ],
            infoButtonText: 'Next',
            infoWelcomeHeaderText: 'Welcome to edSpark!',
            infoWelcomeHeaderBlurb: 'We see this is your first time visiting us, please confirm the following information.',

            name: '',
            email: '',
            roleId: '',
            role: '',
            siteId: '',
            site: {},
            yearLevels: [],
            subjects: [],
            interests: [],
            biography: '',
            avatar: '',
            avatarURL: '',
            hasAvatarURL: false,
            customSiteSearch: [],
            years: [
                { yearLevel: 1, value: 'one' },
                { yearLevel: 2, value: 'two' },
                { yearLevel: 3, value: 'three' },
                { yearLevel: 4, value: 'four' },
                { yearLevel: 5, value: 'five' },
                { yearLevel: 6, value: 'six' },
                { yearLevel: 7, value: 'seven' },
                { yearLevel: 8, value: 'eight' },
                { yearLevel: 9, value: 'nine' },
                { yearLevel: 10, value: 'ten' },
                { yearLevel: 11, value: 'eleven' },
                { yearLevel: 12, value: 'twelve' },
            ],
            allSubjects: [
                'English',
                'Mathematics',
                'Science',
                'HASS',
                'The Arts',
                'Technologies',
                'Health and PE',
                'Languages',
                'Work Studies'
            ],
            digitalTechnologies: [
                'Drones',
                'VR',
                'AR',
                'Robotics',
                'AV systems',
                'IoT'
            ],
            claims: [],
        }
    },

    watch: {
        isFirstVisit: {
            handler (newVal, oldVal) {
                console.log(newVal, oldVal)
                if (newVal !== oldVal ) {
                    if (this.isFirstVisit) {
                        console.log('is first visit...');
                        document.body.style.overflow = 'hidden';
                    } else {
                        console.log('is NOT first visit...');
                        document.body.style.overflow = 'auto';
                    }
                }
            },
            immediate: true
        },
    },

    async created() {

        const idToken = await this.$auth.tokenManager.get('idToken');
        console.log(idToken);
        this.claims = await Object.entries(idToken.claims).map(entry => ({ claim: entry[0], value: entry[ 1 ]}));
        console.log(this.claims);



        /**
         * Set the pre-fill information as much as possible
         */
        this.claims.forEach(claim => {
            // console.log(claim);
            switch (claim.claim) {
                case 'name':
                        this.name = claim.value;
                    break;
                case 'email':
                        this.email = claim.value;
                        this.userStore.checkUser(claim.value);
                    break;
                case 'site':
                        this.siteId = claim.value;
                    break;
                case 'role':
                        this.roleId = claim.value;
                    break;

                default:
                    break;
            }
        });
    },

    mounted() {
        console.log(this.allSites);
    },

    methods: {

        stripTags(content) {
            const element = document.createElement('div');
            element.innerHTML = content
            // console.log(element);
            return element.textContent
        },

        handleTimeString(timeString) {
            const date = new Date(timeString);
            const humanReadableString = date.toLocaleString();
            return humanReadableString;
        },

        handleStep(stepIndex) {
            console.log('the current step is', stepIndex);
            if (stepIndex === 0) {
                this.infoWelcomeHeaderText = 'Welcome to edSpark!';
                this.infoWelcomeHeaderBlurb = 'We see this is your first time visiting us, please confirm the following information.';
                this.infoButtonText = 'Next';
            };

            if (stepIndex === 1) {
                this.infoWelcomeHeaderText = 'Now, a little bit about you.';
                this.infoWelcomeHeaderBlurb = 'Please fill out the following information for a tailored experience. Don\'t worry, you can skip for now a return later.';
                this.infoButtonText = 'Next';
            };

            if (stepIndex === 2) {
                this.infoWelcomeHeaderText = 'Not much further, we\'re nearly there!';
                this.infoWelcomeHeaderBlurb = 'Hang in there, the finish line is in sight!';
                this.infoButtonText = 'Next';
            };

            if (stepIndex === 3) {
                this.infoWelcomeHeaderText = 'We made it!';
                this.infoWelcomeHeaderBlurb = 'Just confirm the following and we\'re all set!   ';
                this.infoButtonText = 'Submit';
            }
        },

        validateStep(stepIndex){
            //run validation of step
            //if step is valid then
            this.steps[stepIndex].step_valid=true;
            this.$refs.multiStepForm.submitStep();
            //else show errors
        },

        submitForm() {
            console.log('Submit the form');

            const data = {
                name: this.name,
                email: this.email,
                role: this.role,
                site: this.site,
                yearLevels: this.yearLevels,
                subjects: this.subjects,
                interests: this.interests,
                biography: this.biography,
                avatar: this.avatar
            }
            console.log(data);
            this.createNewUser(data);
        },

        onChangeFile(event) {
            const target = event.target
            if (target && target.files) {
                this.avatar = target.files[0];

                const reader = new FileReader();

                reader.onload = function(event) {
                    const imageData = event.target.result;
                    localStorage.setItem('image', imageData);
                }

                reader.readAsDataURL(this.avatar);

                console.log(this.avatar)

            }
        },

        closePopup() {
            console.log('close the popup');
            this.isFirstVisit = false;
            // this.$emit('handleFirstLogin', this.isFirstVisit);
        },

        handleSanitizeContent(string, allowedTags) {
            const clean = sanitizeHtml(string, { allowedTags: allowedTags }).trim();
            return clean;
        },

        onSelectedOptionSites(payload) {
            console.log(payload);
            this.site = payload;
        }
    }
}
</script>

<template>
    <div>
        <DashboardHero class="-mt-[140px]" />

        <!-- v-if="this.isFirstVisit" -->
        <div
            :class="isFirstVisit ? 'bg-black' : 'bg-transparent -z-50' "
            class="absolute w-full h-screen z-50 transition-colors duration-1000 left-0 right-0 top-0 bottom-0"
        />

        <div
            v-if="isFirstVisit"
            class="relative"
        >
            <ConfirmInfo
                ref="multiStepForm"
                :steps="steps"
                @onComplete="submitForm"
                @validateStep="validateStep"
                @handleStep="handleStep"
                @closePopup="closePopup"
            >
                <template #formHeader>
                    <h3 class="text-[36px] text-black font-bold">
                        {{ infoWelcomeHeaderText }}
                    </h3>
                    <p class="text-[18px] font-medium">
                        {{ infoWelcomeHeaderBlurb }}
                    </p>
                </template>

                <template #step1>
                    <div>
                        <label for="Name">Your name</label>
                        <input
                            v-model="name"
                            type="text"
                            name="Name"
                            placeholder="Name..."
                        >
                    </div>

                    <div>
                        <label for="Email">Your email</label>
                        <input
                            v-model="email"
                            type="email"
                            name="Email"
                            placeholder="Email..."
                        >
                    </div>

                    <div class="flex flex-col">
                        <label for="Role">Your Role</label>
                        <SearchDropdown
                            class="searchable_dropdown"
                            :options="this.allRoles"
                            :placeholder="'Search for your role...'"
                            name="site"
                            :closeOnOutsideClick="true"
                            @selected="onSelectedOptionRoles"
                        />
                    </div>

                    <div class="flex flex-col">
                        <label for="Role">Your Site</label>
                        <SearchDropdown
                            class="searchable_dropdown"
                            :options="this.allSites"
                            :placeholder="'Search for your site...'"
                            name="site"
                            :closeOnOutsideClick="true"
                            @selected="onSelectedOptionSites"
                        />
                    </div>
                </template>

                <template #step2>
                    <h5 class="text-[18px] font-bold">
                        What year levels do you work with?
                    </h5>
                    <div class="flex gap-6 flex-wrap">
                        <label
                            v-for="(year,index) in years"
                            :key="index"
                            class="flex gap-2"
                            :for="year.value"
                        >
                            <input
                                :id="year.value"
                                v-model="yearLevels"
                                type="checkbox"
                                :value="year.yearLevel"
                            >
                            {{ year.yearLevel }}
                        </label>
                    </div>

                    <h5 class="text-[18px] font-bold">
                        What are your subjects?
                    </h5>
                    <div class="flex gap-6 flex-row flex-wrap w-full">
                        <div
                            v-for="(subject,index) in allSubjects"
                            :key="index"
                            class="flex gap-2"
                        >
                            <input
                                :id="subject"
                                v-model="subjects"
                                type="checkbox"
                                :value="subject"
                            >
                            <label
                                class="shrink-0"
                                :for="subject"
                            >{{ subject }}</label>
                        </div>
                    </div>
                </template>

                <template #step3>
                    <h5 class="text-[18px] font-bold">
                        What digital technologies are you interested in
                    </h5>
                    <div class="flex gap-6 flex-wrap">
                        <div
                            v-for="(tech,index) in digitalTechnologies"
                            :key="index"
                            class="flex gap-2"
                        >
                            <input
                                :id="tech"
                                v-model="interests"
                                type="checkbox"
                                :value="tech"
                            >
                            <label
                                class="shrink-0"
                                :for="tech"
                            >{{ tech }}</label>
                        </div>
                    </div>

                    <h5 class="text-[18px] font-bold">
                        Tell us a little bit about yourself
                    </h5>
                    <div>
                        <textarea
                            id=""
                            v-model="biography"
                            name=""
                            cols="30"
                            rows="8"
                        />
                        <!-- <input type="checkbox" :id="tech" :value="tech" v-model="interests"> -->
                        <label
                            class="shrink-0"
                            :for="tech"
                        >{{ tech }}</label>
                    </div>
                </template>

                <template #step4>
                    <h5>Upload an avatar</h5>
                    <div>
                        <input
                            type="file"
                            name="file"
                            @change="onChangeFile"
                        >

                        <img
                            :src="avatarURL"
                            alt=""
                        >
                    </div>
                </template>

                <template #formFooter>
                    <button
                        class="!bg-[#002856] ml-auto mt-auto px-4 py-2 text-white w-fit"
                        :type="stepIndex === 3 ? 'submit': ''"
                    >
                        {{ infoButtonText }}
                    </button>
                </template>

                <!-- <template #submitButton>
                    <button
                        class="!bg-[#002856] ml-auto mt-auto px-4 py-2 text-white w-fit"
                        type="submit"
                    >
                        Submit
                    </button>
                </template> -->
            </ConfirmInfo>
        </div>

        <SectionHeader
            :classes="'bg-[#339999]'"
            :section="'events'"
        >
            <template #header>
                <h3 class="text-white text-[36px] font-semibold self-center section-header uppercase">
                    New Events
                </h3>
            </template>
            <template #cta>
                <button class="bg-white px-4 py-2 rounded-sm border-2 border-[#339999] text-[#339999] text-[15px] font-medium cursor-pointer hover:text-[#307474] hover:border-2 hover:border-[#307474]">
                    View all events
                </button>
            </template>
        </SectionHeader>

        <div class="px-[81px] py-20">
            <div class="grid grid-cols-3 gap-[24px] w-full">
                <div
                    v-for="(event,index) in events.slice(0, 3)"
                    :key="index"
                    class="col-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[530px] transition-all group card_parent hover:shadow-2xl"
                    @mouseenter="cardHoverToggle = true"
                >
                    <ContentSection>
                        <template #cover>
                            <div
                                :class="`bg-[url('${imageURL}/${event.cover_image}')]`"
                                class="h-36 transition-all bg-cover bg-no-repeat bg-center group-hover:h-0"
                            />
                        </template>
                        <template
                            v-if="event.eventtype_id"
                            #typeTag
                        >
                            <div class="absolute rounded bg-[#DE4668] min-w-[136px] h-[39px] text-white flex flex-row justify-around gap-3 place-items-center -right-3 top-3 px-4">
                                <InPerson v-if="post.type === 'In Person'" />
                                <Virtual v-if="post.type === 'Virtual'" />
                                {{ post.type }}
                            </div>
                        </template>
                        <template #title>
                            {{ event.event_title }}
                        </template>
                        <template #created_at>
                            {{ handleTimeString(event.start_date) }}
                        </template>
                        <template #description>
                            {{ stripTags(event.event_content) }}
                        </template>
                    </ContentSection>
                </div>
            </div>
        </div>

        <SectionHeader
            :classes="'bg-[#1C5CA9]'"
            :section="'software'"
        >
            <template #header>
                <h3 class="text-white text-[36px] font-semibold self-center section-header uppercase">
                    Top Software
                </h3>
            </template>
            <template #cta>
                <button class="bg-white px-4 py-2 rounded-sm border-2 border-[#1C5CA9] text-[#1C5CA9] text-[15px] font-medium cursor-pointer hover:text-[#1a3b64] hover:border-2 hover:border-[#1a3b64]">
                    View all software
                </button>
            </template>
        </SectionHeader>

        <div class="px-[81px] py-20">
            <div class="grid grid-cols-12 gap-[24px] w-full h-full relative group/bg">
                <div class="absolute softwareRobot -z-10 transition-all duration-700 opacity-10 top-1/2 -translate-y-1/2 left-1/3 group-hover/bg:left-[15%] group-hover/bg:scale-125">
                    <SoftwareRobot />
                </div>
                <div class="col-span-5 flex place-items-center">
                    <div class="w-full h-[700px] grid grid-cols-6 grid-rows-2">
                        <div class="col-span-1 row-span-1">
                            <img
                                class=""
                                src="../../assets/images/departmentProvidedAndApproved.png"
                                alt="Department Approved And Provided"
                            >
                        </div>
                        <div class="col-span-5 row-span-1 grid grid-rows-3 h-full">
                            <div class="row-span-1 px-8">
                                <h5 class="text-[21px] font-semibold pt-4">
                                    Department Provided
                                </h5>
                                <p class="w-9/12">
                                    These applications are provided by the department
                                    at no cost to schools
                                </p>
                            </div>
                            <div class="row-span-1" />
                            <div class="row-span-1 flex flex-col h-full px-8">
                                <h5 class="text-[21px] font-semibold mt-auto">
                                    Department Approved
                                </h5>
                                <p class="pb-4 w-9/12">
                                    These applications have been risk assessed and can
                                    be safely used in schools
                                </p>
                            </div>
                        </div>
                        <div class="col-span-2 row-span-1 flex">
                            <img
                                class="h-full pt-12 pb-8 ml-auto"
                                src="../../assets/images/negotiatedDeals.png"
                                alt="Negotiated Deals"
                            >
                        </div>
                        <div class="col-span-3 row-span-1 flex flex-col pl-8">
                            <h5 class="text-[18px] font-semibold mt-auto">
                                Negotiated Deals
                            </h5>
                            <p class="pb-4 w-[85%]">
                                Still risk assessed, these applications have an agreement
                                in place for schools to receive better value. Schools will
                                need to fund purchases
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-span-7">
                    <div class="grid grid-cols-2 grid-rows-2 gap-[24px] w-full h-full">
                        <div
                            v-for="(software,index) in softwareArticles.slice(0, 4)"
                            :key="index"
                            class="col-span-1 row-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl"
                        >
                            <ContentSection>
                                <template #cover>
                                    <!-- TODO: get the images for each resource type -->
                                    <div :class="`bg-[url(${imageURL}/${software.cover_image})]`" class="h-36 transition-all bg-cover bg-no-repeat bg-center group-hover:h-0"></div>
                                    <!-- <div
                                        :class="`bg-[url('http://localhost:8000/storage/uploads/software/edSpark-software-boy-with-virtual-reality-headset-school%203.jpg')]`"
                                        class="h-36 group-hover:h-0 transition-all"
                                    /> -->
                                </template>
                                <template
                                    v-if="software.software_type"
                                    #typeTag
                                >
                                    <div class="absolute -top-3 -right-3" v-if="software.software_type === 'Department Approved'">
                                        <DepartmentApproved />
                                    </div>
                                    <div class="absolute -top-3 -right-3" v-if="software.software_type === 'Department Provided'">
                                        <DepartmentProvided />
                                    </div>
                                </template>
                                <template #title>
                                    {{ software.post_title }}
                                    <span v-if="software.post_title === 'Makers Empire'">
                                        <NegotiatedDeals />
                                    </span>
                                </template>
                                <template
                                    v-if="software.tags"
                                    #negotiatedDeals
                                >
                                    <img
                                        class="w-[36px] h-[36px] absolute top-[18px] right-[24px] group-hover:top-[62px] transition-all"
                                        src="../../assets/images/NegotiatedDealsIcon.png"
                                        alt="Software that includes Negotiated Deals"
                                    >
                                </template>
                                <template #created_at>
                                    {{ handleTimeString(software.created_at) }}
                                </template>
                                <template #description>
                                    {{ stripTags(software.post_content) }}
                                </template>
                            </ContentSection>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <SectionHeader
            :classes="'bg-[#0A7982]'"
            :section="'advice'"
        >
            <template #header>
                <h3 class="text-white text-[36px] font-semibold self-center section-header uppercase">
                    Advice
                </h3>
            </template>
            <template #cta>
                <button class="bg-white px-4 py-2 rounded-sm border-2 border-[#0A7982] text-[#0A7982] text-[15px] font-medium cursor-pointer hover:text-[#15464b] hover:border-2 hover:border-[#15464b]">
                    View all resources
                </button>
            </template>
        </SectionHeader>

        <div class="px-[81px] py-20">
            <div class="grid grid-cols-3 gap-[24px] w-full h-full">
                <div class="col-span-1">
                    <div class="grid grid-cols-3 row-span-4 py-4">
                        <div class="col-span-1 row-span-1">
                            <img
                                class=""
                                src="../../assets/images/WhatIsDag.png"
                                alt="Digital Adoption Group Icon"
                            >
                        </div>
                        <div class="col-span-2 row-span-1 flex place-items-center">
                            <h4 class="text-[24px] font-semibold">
                                What is the D.A.G?
                            </h4>
                        </div>
                        <div class="col-span-3 row-span-3 py-8">
                            <p>
                                The Digital Adoption Group (DAG) is a cross-divisional
                                group that provides holistic and focused advice on
                                digital technologies
                            </p>
                            <p class="mt-8">
                                The objective of the DAG is to support leaders and
                                educators to integrate high-impact technologies by
                                providing system-wide and practical advice on what
                                technology to purchase for teaching and learning
                                and for what purpose.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-span-2">
                    <div class="grid grid-cols-2 grid-rows-1 gap-[24px] w-full h-full">
                        <div
                            v-for="(resource,index) in adviceResources.slice(0, 2)"
                            :key="index"
                            class="col-span-1 row-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl"
                        >
                            <ContentSection>
                                <template #cover>
                                    <div :class="`bg-[url(${imageURL}/${resource.cover_image})]`" class="h-36 transition-all bg-cover bg-no-repeat bg-center group-hover:h-0"></div>
                                    <!-- <div :class="`bg-[url('${resource.cover}')]`" class="h-36 transition-all group-hover:h-0"></div> -->
                                    <!-- <div
                                        :class="`bg-[url('https://picsum.photos/200/300')]`"
                                        class="h-36 group-hover:h-0 transition-all"
                                    /> -->
                                </template>
                                <!-- TODO: Check for advice type, render -->
                                <!-- v-if="resource.type" -->
                                <template #typeTag>
                                    <div class="absolute rounded bg-[#FFC836] min-w-[136px] h-[39px] text-white flex flex-row justify-around gap-3 place-items-center -right-3 top-3 px-4">
                                        <p>D.A.G advice</p>
                                        {{ resource.type }}
                                    </div>
                                </template>
                                <template #title>
                                    {{ resource.post_title }}
                                </template>
                                <template #created_at>
                                    {{ handleTimeString(resource.created_at) }}
                                </template>
                                <template
                                    v-if="resource.post_excerpt"
                                    #description
                                >
                                    {{ handleSanitizeContent(resource.post_excerpt, []) }}
                                </template>
                                <template
                                    v-else
                                    #description
                                >
                                    {{ handleSanitizeContent(resource.post_content, []) }}
                                </template>
                            </ContentSection>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <SectionHeader
            :classes="'bg-[#002858]'"
            :section="'schools'"
        >
            <template #header>
                <h3 class="text-white text-[36px] font-semibold self-center section-header uppercase">
                    Latest School Profiles
                </h3>
            </template>
            <template #cta>
                <button class="bg-white px-4 py-2 rounded-sm border-2 border-[#002858] text-[#002858] text-[15px] font-medium cursor-pointer hover:text-[#0b1829] hover:border-2 hover:border-[#0b1829]">
                    View all schools
                </button>
            </template>
        </SectionHeader>

        <div class="px-[81px] py-20">
            <div class="grid grid-cols-4 gap-[24px] w-full">
                <!-- {{ schools.slice(0, 4) }} -->
                <div
                    v-for="(school,index) in schools.slice(0, 4)"
                    :key="index"

                    class="col-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl"
                >
                    <router-link :to="`/schools/${school.name}`">
                        <ContentSection>
                            <template #cover>
                                <div :class="`bg-[url(${imageURL}/${school.cover_image})]`" class="h-36 transition-all bg-cover bg-no-repeat bg-center group-hover:h-0"></div>

                                <!-- <div
                                    :class="`bg-[url('${school.cover}')]`"
                                    class="h-36 group-hover:h-0 transition-all"
                                /> -->
                            </template>
                            <template #title>
                                {{ school.name }}
                            </template>
                            <template #techUsed>
                                <p class="pt-6 text-black text-[18px] font-medium">
                                    Tech used:
                                </p>
                                <div class=" pt-4 flex flex-row w-full justify-between place-items-center ">
                                    <div
                                        v-for="(technology,index) in school.tech_used"
                                        :key="index"
                                        class="flex"
                                    >
                                        <div v-for="(tech, index) in technology">
                                            <div
                                                v-if="tech.name === 'Mircosoft Teams'"
                                                class="my-auto group/tech schools-tech"
                                            >
                                                <Microsoft /><SchoolsTech :tech-hover="tech" />
                                            </div>
                                            <div
                                                v-if="tech.name === '3D Printing'"
                                                class="my-auto group/tech schools-tech"
                                            >
                                                <ThreeDPrintingIcon /><SchoolsTech :tech-hover="tech" />
                                            </div>
                                            <div
                                                v-if="tech.name === 'Apple'"
                                                class="my-auto group/tech schools-tech"
                                            >
                                                <AppleIcon /><SchoolsTech :tech-hover="tech" />
                                            </div>
                                            <div
                                                v-if="tech.name === 'Frog'"
                                                class="my-auto group/tech schools-tech"
                                            >
                                                <FrogIcon /><SchoolsTech :tech-hover="tech" />
                                            </div>
                                            <div
                                                v-if="tech.name === 'IoT'"
                                                class="my-auto group/tech schools-tech"
                                            >
                                                <IoTIcon /><SchoolsTech :tech-hover="tech" />
                                            </div>
                                            <div
                                                v-if="tech.name === 'Robotics'"
                                                class="my-auto group/tech schools-tech"
                                            >
                                                <RoboticsIcon /><SchoolsTech :tech-hover="tech" />
                                            </div>
                                            <div
                                                v-if="tech.name === 'AR'"
                                                class="my-auto group/tech schools-tech"
                                            >
                                                <ARIcon /><SchoolsTech :tech-hover="tech" />
                                            </div>
                                            <div
                                                v-if="tech.name === 'VR'"
                                                class="my-auto group/tech schools-tech"
                                            >
                                                <VRIcon /><SchoolsTech :tech-hover="tech" />
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </template>
                        </ContentSection>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
