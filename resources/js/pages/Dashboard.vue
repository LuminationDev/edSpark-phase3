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

    /**
     * IMPORT SVGS
     */
    import InPerson from '../components/svg/InPerson.vue';
    import Virtual from '../components/svg/Virtual.vue';
    import DepartmentApproved from '../components/svg/DepartmentApproved.vue';
    import DepartmentProvided from '../components/svg/DepartmentProvided.vue';
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

    export default {
        setup() {
            const adviceStore = useAdviceStore();
            const softwareStore = useSoftwareStore();
            const userStore = useUserStore();

            adviceStore.loadDashboardResources();
            softwareStore.loadArticles();
            userStore.loadCurrentUser();

            const adviceResources = computed(() => {
                return adviceStore.getResources;
            });

            const softwareArticles = computed(() => {
                console.log(softwareStore.getArticles);
                return softwareStore.getArticles;
            });

            const cardHoverToggle = ref(false);

            /**
             * Change this to TRUE to simulate the First Login Experience
             */
            const isFirstVisit = ref(false);



            const createNewUser = async (data) => {
                const result = await userStore.createUser(data);
                return result;
            }

            return {
                createNewUser,
                userStore,
                adviceStore,
                softwareStore,
                adviceResources,
                softwareArticles,
                cardHoverToggle,
                isFirstVisit,
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
            }
        },

        components: {
            DashboardHero,
            SectionHeader,
            ContentSection,
            SchoolsTech,
            InPerson,
            Virtual,
            DepartmentApproved,
            DepartmentProvided,
            Microsoft,
            ThreeDPrintingIcon,
            AppleIcon,
            FrogIcon,
            IoTIcon,
            RoboticsIcon,
            ARIcon,
            VRIcon,
            SoftwareRobot,
            ConfirmInfo
        },

        data() {
            return {
                posts: [
                    {
                        title: 'Im a Post',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Quisque quis luctus turpis. Nam et arcu facilisis, blandit felis ut, egestas dolor. Cras at dignissim augue. Curabitur placerat fermentum mollis. Vestibulum mollis facilisis placerat.',
                        created_at: '25th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                        type: 'Virtual'
                    },
                    {
                        title: 'Test content title',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus.',
                        created_at: '14th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                        type: 'In Person'
                    },
                    {
                        title: 'Try out a slightly longer title',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Nam et arcu facilisis, blandit felis ut, egestas dolor.',
                        created_at: '29th Jan 2023',
                        cover: 'https://picsum.photos/200/300',
                        type: 'Virtual'
                    }
                ],
                schools: [
                    {
                        full_name: 'Adelaide High School',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Quisque quis luctus turpis. Nam et arcu facilisis, blandit felis ut, egestas dolor. Cras at dignissim augue. Curabitur placerat fermentum mollis. Vestibulum mollis facilisis placerat.',
                        created_at: '25th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                        tech_used: [
                            {
                                name: 'VR',
                                description: 'is a simulated experience that employs pose tracking and 3D near-eye displays to give the user an immersive feel of a virtual world.',
                                category: 'Emerging tech',
                            },
                            {
                                name: 'AR',
                                description: '(Augmented Reality) is an interactive experience that combines the real world and computer-generated content.',
                                category: 'Emerging tech',
                            },
                            {
                                name: '3D Printing',
                                description: 'or additive manufacturing is the construction of a three-dimensional object from a CAD model or a digital 3D model.',
                                category: 'Emerging tech',
                            },
                            {
                                name: 'Mircosoft Teams',
                                description: 'is a proprietary business communication platform developed by Microsoft, as part of the Microsoft 365 family of products.',
                                category: 'Platforms',
                            },
                        ]
                    },
                    {
                        full_name: 'East Adelaide School',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus.',
                        created_at: '14th Feb 2023',
                        cover: 'https://picsum.photos/200/300',
                        tech_used: [
                            {
                                name: 'Mircosoft Teams',
                                description: 'is a proprietary business communication platform developed by Microsoft, as part of the Microsoft 365 family of products.',
                                category: 'Platforms',
                            },
                            {
                                name: 'Robotics',
                                description: 'is an interdisciplinary branch of computer science and engineering. Robotics involves design, construction, operation, and use of robots. ',
                                category: 'Emerging tech',
                            },
                            {
                                name: 'Frog',
                                description: 'provides whole school systems for teaching, learning and communication. Additionally we create platforms that allow businesses, MATs and schools to create and deliver online training',
                                category: 'Platforms'
                            },
                            {
                                name: 'IoT',
                                description: '(or "Internet of Things") describes physical objects with sensors, processing ability, software, and other technologies that connect and exchange data with other devices and systems over the Internet or other communications networks.',
                                category: 'Emerging tech',
                            },
                        ]
                    },
                    {
                        full_name: 'North Adelaide Primary School',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Nam et arcu facilisis, blandit felis ut, egestas dolor.',
                        created_at: '29th Jan 2023',
                        cover: 'https://picsum.photos/200/300',
                        tech_used: [
                            {
                                name: 'Apple',
                                description: 'technology and resources empower every kind of educator — and every kind of student — to learn, create and define their own success.',
                                category: 'Platforms'
                            },
                            {
                                name: 'Frog',
                                description: 'provides whole school systems for teaching, learning and communication. Additionally we create platforms that allow businesses, MATs and schools to create and deliver online training',
                                category: 'Platforms'
                            },
                            {
                                name: 'VR',
                                description: 'is a simulated experience that employs pose tracking and 3D near-eye displays to give the user an immersive feel of a virtual world.',
                                category: 'Emerging tech',
                            },
                            {
                                name: 'IoT',
                                description: '(or "Internet of Things") describes physical objects with sensors, processing ability, software, and other technologies that connect and exchange data with other devices and systems over the Internet or other communications networks.',
                                category: 'Emerging tech',
                            },
                        ]
                    },
                    {
                        full_name: 'Adelaide Botanic High School',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse feugiat metus auctor, tempor eros ut, faucibus augue. Integer laoreet metus ac vulputate dictum. Nulla maximus et purus nec ullamcorper. Donec non ligula lacus. Nam et arcu facilisis, blandit felis ut, egestas dolor.',
                        created_at: '29th Jan 2023',
                        cover: 'https://picsum.photos/200/300',
                        tech_used: [
                            {
                                name: 'VR',
                                description: 'is a simulated experience that employs pose tracking and 3D near-eye displays to give the user an immersive feel of a virtual world.',
                                category: 'Emerging tech',
                            },
                            {
                                name: 'AR',
                                description: '(Augmented Reality) is an interactive experience that combines the real world and computer-generated content.',
                                category: 'Emerging tech',
                            },
                            {
                                name: '3D Printing',
                                description: 'or additive manufacturing is the construction of a three-dimensional object from a CAD model or a digital 3D model.',
                                category: 'Emerging tech',
                            },
                            {
                                name: 'Mircosoft Teams',
                                description: 'is a proprietary business communication platform developed by Microsoft, as part of the Microsoft 365 family of products.',
                                category: 'Platforms',
                            },
                        ]
                    }
                ],
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
                role: '',
                site: '',
                yearLevels: [],
                subjects: [],
                interests: [],
                biography: '',
                avatar: '',
                avatarURL: '',
                hasAvatarURL: false,
                claims: [],
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
                ]
            }
        },

        created() {

        },

        methods: {
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
                    biography: this.biography
                }
                console.log(data);
                this.createNewUser(data);
            },

            onChangeFile(event) {
                const target = event.target
                if (target && target.files) {
                    this.avatar = target.files[0];
                    console.log(this.avatar);
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
        },

        async created () {

            const auth = getCurrentInstance().appContext.app.config.globalProperties.$auth


            const idToken = await auth.token?.parseFromUrl()
            .then(async res => {
                const { idToken } = res.tokens;
                const { accessToken } = res.tokens;

                console.log(`Hi ${idToken.claims.email}!`);
                console.log(`accessToken ${accessToken}!`);

            }).catch(err => {
                console.log('There is a serious error');
                console.error(err);
            })

            // const idToken = await this.$auth.tokenManager.get('idToken');
            // const token = this.$auth.authStateManager.getAuthState();
            // console.log(token);

            // console.log(localStorage.getItem('okta-shared-transaction-storage'));

            // const oktaSharedTransactionStorage = JSON.parse(localStorage.getItem('okta-shared-transaction-storage'));

            // console.log(Object.entries(oktaSharedTransactionStorage));

            // // this.claims = await Object.entries(idToken.claims).map(entry => ({ claim: entry[0], value: entry[1] }))

            // this.claims = await Object.entries(oktaSharedTransactionStorage).map(entry => ({ claim: entry[0], value: entry[1] }))

        }
    }
</script>

<template>
    <div>
        <DashboardHero class="-mt-[140px]" />

        <!-- v-if="this.isFirstVisit" -->
        <div  :class="this.isFirstVisit ? 'bg-black' : 'bg-transparent -z-50' " class="absolute w-full h-screen z-50 transition-colors duration-1000 left-0 right-0 top-0 bottom-0">

        </div>

        <div v-if="this.isFirstVisit" class="relative">
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
                        {{ this.infoWelcomeHeaderText }}
                    </h3>
                    <p class="text-[18px] font-medium">
                        {{ this.infoWelcomeHeaderBlurb }}
                    </p>
                </template>

                <template #step1>
                    <div>
                        <label for="Name">Your name</label>
                        <input type="text" name="Name" placeholder="Name..." v-model="name">
                    </div>

                    <div>
                        <label for="Email">Your email</label>
                        <input type="email" name="Email" placeholder="Email..." v-model="email">
                    </div>

                    <div>
                        <label for="Role">Your Role</label>
                        <input type="text" name="Role" placeholder="Role..." v-model="role">
                    </div>

                    <div>
                        <label for="Role">Your Site</label>
                        <input type="text" name="Site" placeholder="Site..." v-model="site">
                    </div>

                </template>

                <template #step2>

                    <h5 class="text-[18px] font-bold">What year levels do you work with?</h5>
                    <div class="flex gap-6 flex-wrap">
                        <label class="flex gap-2" :for="year.value" v-for="year in this.years">
                            <input type="checkbox" :id="year.value" :value="year.yearLevel" v-model="yearLevels">
                            {{ year.yearLevel }}
                        </label>
                    </div>

                    <h5 class="text-[18px] font-bold">What are your subjects?</h5>
                    <div class="flex gap-6 flex-row flex-wrap w-full">
                        <div class="flex gap-2" v-for="subject in this.allSubjects">
                            <input type="checkbox" :id="subject" :value="subject" v-model="subjects">
                            <label class="shrink-0" :for="subject">{{ subject }}</label>
                        </div>
                    </div>

                </template>

                <template #step3>
                    <h5 class="text-[18px] font-bold">What digital technologies are you interested in</h5>
                    <div class="flex gap-6 flex-wrap">
                        <div class="flex gap-2" v-for="tech in this.digitalTechnologies">
                            <input type="checkbox" :id="tech" :value="tech" v-model="interests">
                            <label class="shrink-0" :for="tech">{{ tech }}</label>
                        </div>

                    </div>

                    <h5 class="text-[18px] font-bold">Tell us a little bit about yourself</h5>
                    <div>
                        <textarea name="" id="" cols="30" rows="8" v-model="biography"></textarea>
                        <!-- <input type="checkbox" :id="tech" :value="tech" v-model="interests"> -->
                        <label class="shrink-0" :for="tech">{{ tech }}</label>
                    </div>
                </template>

                <template #step4>
                    <h5>Upload an avatar</h5>
                    <div>
                        <input type="file" @change="onChangeFile" name="file">

                        <img :src="this.avatarURL" alt="">
                    </div>
                </template>

                <template #formFooter>
                    <button
                        class="!bg-[#002856] ml-auto mt-auto px-4 py-2 text-white w-fit"
                        type="submit"
                    >
                        {{ this.infoButtonText }}
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

        <SectionHeader :classes="'bg-[#339999]'" :section="'events'">
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
                <div v-for="post in this.posts" @mouseenter="cardHoverToggle = true" class="col-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[530px] transition-all group card_parent hover:shadow-2xl">
                    <ContentSection>
                        <template #cover>
                            <div :class="`bg-[url('${post.cover}')]`" class="h-full group-hover:h-0 transition-all"></div>
                        </template>
                        <template #typeTag v-if="post.type">
                            <div class="absolute rounded bg-[#DE4668] min-w-[136px] h-[39px] text-white flex flex-row justify-around gap-3 place-items-center -right-3 top-3 px-4">
                                <InPerson v-if="post.type === 'In Person'" />
                                <Virtual v-if="post.type === 'Virtual'"/>
                                {{ post.type }}
                            </div>
                        </template>
                        <template #title>
                            {{ post.title }}
                        </template>
                        <template #created_at>
                            {{ post.created_at }}
                        </template>
                        <template #description>
                            {{  post.description  }}
                        </template>
                    </ContentSection>
                </div>
            </div>
        </div>

        <SectionHeader :classes="'bg-[#1C5CA9]'" :section="'software'">
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
                            <img class="" src="../../assets/images/departmentProvidedAndApproved.png" alt="Department Approved And Provided">
                        </div>
                        <div class="col-span-5 row-span-1 grid grid-rows-3 h-full">
                            <div class="row-span-1 px-8">
                                <h5 class="text-[21px] font-semibold pt-4">Department Provided</h5>
                                <p class="w-9/12">
                                    These applications are provided by the department
                                    at no cost to schools
                                </p>
                            </div>
                            <div class="row-span-1"></div>
                            <div class="row-span-1 flex flex-col h-full px-8">
                                <h5 class="text-[21px] font-semibold mt-auto">Department Approved</h5>
                                <p class="pb-4 w-9/12">
                                    These applications have been risk assessed and can
                                    be safely used in schools
                                </p>
                            </div>
                        </div>
                        <div class="col-span-2 row-span-1 flex">
                            <img class="h-full pt-12 pb-8 ml-auto" src="../../assets/images/negotiatedDeals.png" alt="Negotiated Deals">
                        </div>
                        <div class="col-span-3 row-span-1 flex flex-col pl-8">
                            <h5 class="text-[18px] font-semibold mt-auto">Negotiated Deals</h5>
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
                        <div class="col-span-1 row-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl" v-for="software in this.softwareArticles.slice(0, 4)">
                            <ContentSection>
                                <template #cover>
                                    <!-- TODO: get the images for each resource type -->
                                    <!-- <div :class="`bg-[url('${software.cover}')]`" class="h-36 transition-all group-hover:h-0"></div> -->
                                    <div :class="`bg-[url('https://picsum.photos/200/300')]`" class="h-36 group-hover:h-0 transition-all"></div>
                                </template>
                                <template #typeTag v-if="software.type">
                                    <div class="absolute -top-3 -right-3">
                                        <DepartmentApproved />
                                    </div>
                                    <div class="absolute -top-3 -right-3">
                                        <DepartmentProvided />
                                    </div>
                                </template>
                                <template #title>
                                    {{ software.post_title }}
                                </template>
                                <template #negotiatedDeals v-if="software.tags">
                                    <img class="w-[36px] h-[36px] absolute top-[18px] right-[24px] group-hover:top-[62px] transition-all" src="../../assets/images/NegotiatedDealsIcon.png" alt="Software that includes Negotiated Deals">
                                </template>
                                <template #created_at>
                                    {{ handleTimeString(software.created_at) }}
                                </template>
                                <template #description>
                                    {{  software.post_content  }}
                                </template>
                            </ContentSection>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <SectionHeader :classes="'bg-[#0A7982]'" :section="'advice'">
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
                            <img class="" src="../../assets/images/WhatIsDag.png" alt="Digital Adoption Group Icon">
                        </div>
                        <div class="col-span-2 row-span-1 flex place-items-center">
                            <h4 class="text-[24px] font-semibold">What is the D.A.G?</h4>
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
                        <div class="col-span-1 row-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl" v-for="resource in this.adviceResources.slice(0, 2)">
                            <ContentSection>
                                <template #cover>
                                    <!-- <div :class="`bg-[url('${resource.cover}')]`" class="h-36 transition-all group-hover:h-0"></div> -->
                                    <div :class="`bg-[url('https://picsum.photos/200/300')]`" class="h-36 group-hover:h-0 transition-all"></div>
                                </template>
                                <!-- TODO: Check for advice type, render -->
                                <!-- v-if="resource.type" -->
                                <template #typeTag >
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
                                <template #description v-if="resource.post_excerpt">
                                    {{ this.handleSanitizeContent(resource.post_excerpt, []) }}
                                </template>
                                <template #description v-else>
                                    {{ this.handleSanitizeContent(resource.post_content, []) }}
                                </template>
                            </ContentSection>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <SectionHeader :classes="'bg-[#002858]'" :section="'schools'">
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
                <div v-for="school in this.schools" class="col-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl">
                    <router-link :to="`/schools/${school.full_name}`">
                        <ContentSection>
                            <template #cover>
                                <div :class="`bg-[url('${school.cover}')]`" class="h-36 group-hover:h-0 transition-all"></div>
                            </template>
                            <template #title>
                                {{ school.full_name }}
                            </template>
                            <template #techUsed>
                                <p class="pt-6 text-black text-[18px] font-medium">Tech used:</p>
                                <div class=" pt-4 flex flex-row w-full justify-between place-items-center ">
                                    <div class="flex" v-for="tech in school.tech_used">
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'Mircosoft Teams'"><Microsoft /><SchoolsTech :techHover="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === '3D Printing'"><ThreeDPrintingIcon /><SchoolsTech :techHover="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'Apple'"><AppleIcon /><SchoolsTech :techHover="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'Frog'"><FrogIcon /><SchoolsTech :techHover="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'IoT'"><IoTIcon /><SchoolsTech :techHover="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'Robotics'"><RoboticsIcon /><SchoolsTech :techHover="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'AR'"><ARIcon /><SchoolsTech :techHover="tech"/></div>
                                        <div class="my-auto group/tech schools-tech" v-if="tech.name === 'VR'"><VRIcon /><SchoolsTech :techHover="tech"/></div>
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

<style>
    input, textarea {
        width: 100% !important;
        padding: .75rem 1.5rem !important;
        border: solid 0.5px black !important;
    }
</style>
