<script setup>
/**
 * Imports and other things necessary for the sign on for the first time form.
 * In this form we are handling user checks etc
 */
import axios from 'axios';
/**
 * Consider setting up all imports at the top, in one place for consistency.
 * Not like this weird, import and declare situation I have here...
 * Any input would be greatly appreciated!!
 */
import SearchDropdown from 'search-dropdown-vue';
import {computed, onMounted,reactive, ref} from 'vue';
/**
 * Import and initialise stores
 * Import and initialise vue router
 */
import {useRouter} from 'vue-router';

import {serverURL} from "@/js/constants/serverUrl";

import ErrorHandler from '../global/ErrorHandler.vue';
import GenericSelector from '../selector/GenericSelector.vue';
import ConfirmInfo from './ConfirmInfo.vue';

const router = useRouter();

import {useSchoolsStore} from '@/js/stores/useSchoolsStore';
import {useSiteStore} from '@/js/stores/useSiteStore';
import {useUserStore} from '@/js/stores/useUserStore';

const siteStore = useSiteStore();
const userStore = useUserStore();
const schoolStore = useSchoolsStore();

/**
 * URL stuff
 */
const imageURL = import.meta.env.VITE_SERVER_IMAGE_API;

/**
 * User details props
 */
const props = defineProps({
    userDetails: {
        type: Object,
        required: true
    },
});

/**
 * Set the popup form ref for access later
 */
const multiStepForm = ref(null);

/**
 * Set all elements/details/prefill data of the form
 *
 * Form step data
 *
 * Roles
 *
 * Sites
 *
 * Form selection fields (year levels, subjects, interests)
 *
 * Gathered user information
 */
const formStepData = reactive({
    infoWelcomeHeaderText: 'Welcome to edSpark!',
    infoWelcomeHeaderBlurb: 'We see this is your first time visiting us, please confirm the following information.',
    infoButtonText: 'Next',
});

/**
 * Handle gathering of all roles and sites
 */
const allRoles = ref([]);
const allSites = ref([]);
const getAllRolesAndSites = async () => {
    allRoles.value = await userStore.fetchAllRoles();
    const everySiteAvailable = await siteStore.loadSites();
    const adjustedSiteArr = [];
    everySiteAvailable.forEach(site => {
        if (site.category_code === 'SCHL' || site.category_code === 'PRESC') {
            adjustedSiteArr.push({id: site.site_id, name: site.site_name});
        }
    });

    allSites.value = adjustedSiteArr;
    return true;
};
getAllRolesAndSites();

/**
 * Form selection fields
 */
const formFieldData = reactive({
    years: [
        {yearLevel: 1, value: 'one'},
        {yearLevel: 2, value: 'two'},
        {yearLevel: 3, value: 'three'},
        {yearLevel: 4, value: 'four'},
        {yearLevel: 5, value: 'five'},
        {yearLevel: 6, value: 'six'},
        {yearLevel: 7, value: 'seven'},
        {yearLevel: 8, value: 'eight'},
        {yearLevel: 9, value: 'nine'},
        {yearLevel: 10, value: 'ten'},
        {yearLevel: 11, value: 'eleven'},
        {yearLevel: 12, value: 'twelve'},
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
})

/**
 * User details to gather
 */
const newUserData = reactive({
    name: Object.keys(props.userDetails).length >= 0 ? props.userDetails.name : '',
    email: Object.keys(props.userDetails).length >= 0 ? props.userDetails.email : '',
    role: {},
    site: {},
    yearLevels: [],
    subjects: [],
    interests: [],
    biography: '',
    avatar: '',
    avatarUrl: '',
});

/**
 * User details to gather
 */
const newSchoolData = reactive({
    schoolName: '',
    schoolMessage: '',
    coverImage: {},
    coverImageUrl: '',
    logo: {},
    logoUrl: '',
    techUsed: []
});


const onSelectedOptionSites = (payload) => {
    newUserData.site = payload;
    newSchoolData.site = payload;
    console.log(newUserData.site);
    console.log(newSchoolData.site);
};

const onSelectedOptionRoles = (payload) => {
    console.log(payload);
    newUserData.role = payload;
    newSchoolData.role = payload;
    console.log(newUserData.role);
};

/**
 * Set a ref for the error mesage
 */
const errorMessage = ref('');

/**
 * Handle image upload
 */
const showUploadedAvatar = ref(false);
const showUploadedLogo = ref(false);
const showUploadedCover = ref(false);
const onChangeFile = async (selector, event) => {
    console.log(event);
    const target = event.target

    if (target.files[0].size > 5000000) {
        errorMessage.value = 'Sorry, that image is larger than 5mb. Please try another.';
        return;
    } else {
        if (target && target.files) {
            const reader = new FileReader();
            reader.readAsDataURL(target.files[0]);
            errorMessage.value = '';
            reader.onload = async function (event) {
                const imageData = event.target.result;

                console.log(imageData.length);

                switch (selector) {
                case 'schoolLogo':
                    // localStorage.setItem('schoolLogo', imageData);
                    newSchoolData.logo = target.files[0];
                    console.log(axiosImageHelper(target.files[0]));
                    newSchoolData.logoUrl = await axiosImageHelper(target.files[0]);
                    showUploadedLogo.value = true;
                    break;

                case 'schoolCover':
                    // localStorage.setItem('schoolCover', imageData);
                    newSchoolData.coverImage = target.files[0];
                    newSchoolData.coverImageUrl = await axiosImageHelper(target.files[0]);
                    showUploadedCover.value = true;
                    break;

                case 'userAvatar':
                    // localStorage.setItem('userAvatar', imageData);
                    newUserData.avatar = target.files[0];
                    newUserData.avatarUrl = await axiosImageHelper(target.files[0]);
                    showUploadedAvatar.value = true;
                    break;

                default:
                    break;
                }
            }
        }
    }
    ;
};

const axiosImageHelper = async (image) => {
    return new Promise(async (resolve, reject) => {
        await axios({
            method: 'POST',
            url: API_ENDPOINTS.IMAGE.UPLOAD_IMAGE,
            data: {
                type: 'school',
                image: image
            },
            headers: {"Content-Type": "multipart/form-data"}
        }).then(response => {
            console.log(response.data.file.url);
            resolve(response.data.file.url);
        }).catch(error => {
            reject(error);
        });
    });
}

const handleChangeImage = (creatine) => {
    switch (creatine) {
    case 'schoolLogo':
        showUploadedLogo.value = false;
        newSchoolData.logoUrl = '';
        localStorage.removeItem('schoolLogo');
        break;

    case 'schoolCover':
        showUploadedCover.value = false;
        newSchoolData.coverImageUrl = '';
        localStorage.removeItem('schoolCover');
        break;

    case 'userAvatar':
        showUploadedAvatar.value = false;
        newUserData.avatarUrl = '';
        localStorage.removeItem('userAvatar');
        break;

    default:
        break;
    }
}

/**
 * Set the form steps
 */
const steps = ref([
    {'step_no': 1, 'step_valid': false, 'step_skip': true},
    {'step_no': 2, 'step_valid': false, 'step_skip': true},
    {'step_no': 3, 'step_valid': false, 'step_skip': true},
    {'step_no': 4, 'step_valid': false, 'step_skip': true},
]);

/**
 * Handle all functions
 *
 * They do things and even some stuff
 */

/**
 *  Create a new user
 */
// const createNewUser = async (data) => {
//     return await userStore.createUser(data);
// };

/**
 * Update a new user
 */
const updateNewUser = async (data) => {
    return await userStore.updateFirstTimeVisit(data);
}

/**
 * Submit button click
 */
const submitForm = async () => {
    const data = {
        name: newUserData.name,
        email: newUserData.email,
        role: newUserData.role,
        site: newUserData.site,
        yearLevels: newUserData.yearLevels,
        subjects: newUserData.subjects,
        interests: newUserData.interests,
        biography: newUserData.biography,
        avatar: newUserData.avatar
    };

    await updateNewUser(data);
    schoolStore.setNewSchoolOnSignIn(newSchoolData);

    /**
     * This check needs to be brought forward in the process
     */
    if (data.role.name === 'School Principal') {
        router.push({
            name: 'school-single',
            params: {
                name: newSchoolData.schoolName
            }
        });
    }
};

/**
 * Handle step validation here
 */
const validateStep = (stepIndex) => {
    /**
     * Run step validation.
     * If true, set as true
     */
    steps[stepIndex].step_valid = true;
    multiStepForm.value.submitStep(); // Potential error issues here...
};

/**
 * Provide a ref to handle rendering depending on the users role (handle principals differently)
 */
const isPrincipal = ref(false);

/**
 * Handle steps using step index
 */
const handleStep = (stepIndex) => {
    console.log('the current step is', stepIndex);
    if (stepIndex === 0) {
        formStepData.infoWelcomeHeaderText = 'Welcome to edSpark!';
        formStepData.infoWelcomeHeaderBlurb = 'We see this is your first time visiting us, please confirm the following information.';
        formStepData.infoButtonText = 'Next';
    }


    if (stepIndex === 1) {
        if (newUserData.role.name === 'School Principal') {
            isPrincipal.value = true;
            newSchoolData.schoolName = newUserData.site.name;
            formStepData.infoWelcomeHeaderText = 'Thanks! Now we\'ll get a little bit of information for your school profile.';
            formStepData.infoWelcomeHeaderBlurb = 'If you\'d prefer, you can nominate one your staff members to manage this information and profile for you.';
            formStepData.infoButtonText = 'Next';
        } else {
            formStepData.infoWelcomeHeaderText = 'Now, a little bit about you.';
            formStepData.infoWelcomeHeaderBlurb = 'Please fill out the following information for a tailored experience. Don\'t worry, you can skip for now a return later.';
            formStepData.infoButtonText = 'Next';
        }
    }

    if (stepIndex === 2) {
        if (newUserData.role.name === 'School Principal') {
            isPrincipal.value = true;
            formStepData.infoWelcomeHeaderText = 'Upload a logo and a cover image for your school.';
            formStepData.infoWelcomeHeaderBlurb = 'Please use a high resolution image for the cover photo. For the logo, please use an image width of at least 200 pixels';
            formStepData.infoButtonText = 'Next';
        } else {
            formStepData.infoWelcomeHeaderText = 'Not much further, we\'re nearly there!';
            formStepData.infoWelcomeHeaderBlurb = 'Hang in there, the finish line is in sight!';
            formStepData.infoButtonText = 'Next';
        }
    }

    if (stepIndex === 3) {
        if (newUserData.role.name === 'School Principal') {
            formStepData.infoWelcomeHeaderText = 'Thanks! Now we\'ll just get your avatar sorted out!';
            formStepData.infoWelcomeHeaderBlurb = 'Please upload your avatar';
            formStepData.infoButtonText = 'Submit';
        } else {
            formStepData.infoWelcomeHeaderText = 'We made it!';
            formStepData.infoWelcomeHeaderBlurb = 'Just confirm the following and we\'re all set!';
            formStepData.infoButtonText = 'Submit';
        }
    }
};

const emits = defineEmits(['onClosePopup'])

/**
 * Close the popup
 */
const closePopup = () => {
    emits('onClosePopup');
}

</script>

<template>
    <ConfirmInfo
        ref="multiStepForm"
        :steps="steps"
        @on-complete="submitForm"
        @validate-step="validateStep"
        @handle-step="handleStep"
        @close-popup="closePopup"
    >
        <template #formHeader>
            <h3 class="font-bold text-[36px] text-black">
                {{ formStepData.infoWelcomeHeaderText }}
            </h3>
            <p class="font-medium text-[18px]">
                {{ formStepData.infoWelcomeHeaderBlurb }}
            </p>
        </template>
        <!-- Step 1: New users data. Name, email, Role and Site -->
        <template #step1>
            <div>
                <label
                    class="font-bold text-[18px]"
                    for="Name"
                >Your name</label>
                <input
                    v-model="newUserData.name"
                    type="text"
                    name="Name"
                    placeholder="Name..."
                >
            </div>

            <div>
                <label
                    class="font-bold text-[18px]"
                    for="Email"
                >Your email</label>
                <input
                    v-model="newUserData.email"
                    type="email"
                    name="Email"
                    placeholder="Email..."
                    disabled
                >
            </div>

            <div class="flex flex-col">
                <label
                    class="font-bold text-[18px]"
                    for="Role"
                >Your Role</label>
                <SearchDropdown
                    class="searchable_dropdown"
                    :options="allRoles"
                    :placeholder="'Search for your role...'"
                    name="site"
                    :close-on-outside-click="true"
                    @selected="onSelectedOptionRoles"
                />
            </div>

            <div class="flex flex-col">
                <label
                    class="font-bold text-[18px]"
                    for="site"
                >Your Site</label>
                <SearchDropdown
                    class="searchable_dropdown"
                    :options="allSites"
                    :placeholder="'Search for your site...' "
                    name="site"
                    :close-on-outside-click="true"
                    @selected="onSelectedOptionSites"
                />
            </div>
        </template>
        <!-- Step 2: If role is principal, set school name and motto, if not principal (means a regular staff), choose yr levels and subjects  -->
        <template
            v-if="!isPrincipal"
            #step2
        >
            <h5 class="font-bold text-[18px]">
                What year levels do you work with?
            </h5>
            <div class="flex flex-wrap gap-6">
                <label
                    v-for="(year,index) in formFieldData.years"
                    :key="index"
                    class="flex font-bold gap-2 text-[18px]"
                    :for="year.value"
                >
                    <input
                        :id="year.value"
                        v-model="newUserData.yearLevels"
                        type="checkbox"
                        :value="year.yearLevel"
                    >
                    {{ year.yearLevel }}
                </label>
            </div>

            <h5 class="font-bold text-[18px]">
                What are your subjects?
            </h5>
            <div class="flex flex-row flex-wrap gap-6 w-full">
                <div
                    v-for="(subject,index) in formFieldData.allSubjects"
                    :key="index"
                    class="flex gap-2"
                >
                    <input
                        :id="subject"
                        v-model="newUserData.subjects"
                        type="checkbox"
                        :value="subject"
                    >
                    <label
                        class="font-bold shrink-0 text-[18px]"
                        :for="subject"
                    >{{ subject }}</label>
                </div>
            </div>
        </template>

        <template
            v-else
            #step2
        >
            <div>
                <label
                    class="font-bold text-[18px]"
                    for="SchoolName"
                >School name</label>
                <input
                    v-model="newSchoolData.schoolName"
                    type="text"
                    name="SchoolName"
                    placeholder="Name..."
                >
            </div>

            <div>
                <h5 class="font-bold text-[18px]">
                    Does your school have a motto?
                </h5>
                <textarea
                    id=""
                    v-model="newSchoolData.schoolMessage"
                    name=""
                    cols="30"
                    rows="8"
                />
            </div>
        </template>
        <!-- Step 3: if pricipal, upload school logo and add cover photo -->
        <!-- Step 3: if regular staff: choose tech interest, biography -->

        <template
            v-if="!isPrincipal"
            #step3
        >
            <h5 class="font-bold text-[18px]">
                What digital technologies are you interested in
            </h5>
            <div class="flex flex-wrap gap-6">
                <div
                    v-for="(tech,index) in formFieldData.digitalTechnologies"
                    :key="index"
                    class="flex gap-2"
                >
                    <input
                        :id="tech"
                        v-model="newUserData.interests"
                        type="checkbox"
                        :value="tech"
                    >
                    <label
                        class="shrink-0"
                        :for="tech"
                    >{{ tech }}</label>
                </div>
            </div>

            <h5 class="font-bold text-[18px]">
                Tell us a little bit about yourself
            </h5>
            <div>
                <textarea
                    id=""
                    v-model="newUserData.biography"
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

        <template
            v-else
            #step3
        >
            <h5 class="font-bold text-[18px]">
                Upload your school logo
            </h5>
            <div>
                <input
                    v-if="!showUploadedLogo"
                    type="file"
                    name="file"
                    @change="onChangeFile('schoolLogo', $event)"
                >

                <div
                    v-else
                    class="flex flex-row gap-6 w-full"
                >
                    <div class="h-[150px] overflow-hidden rounded-full w-[150px]">
                        <img
                            :src="`${imageURL}/${newSchoolData.logoUrl}`"
                            alt=""
                        >
                    </div>

                    <!-- <div class=""> -->

                    <button
                        class="h-fit mb-auto ml-auto px-7 py-3 hover:underline"
                        @click="handleChangeImage('schoolLogo')"
                    >
                        Change
                    </button>
                    <!-- </div> -->
                </div>

                <ErrorHandler
                    v-if="errorMessage.length > 0"
                    :error-message="errorMessage"
                />
            </div>

            <h5 class="font-bold text-[18px]">
                Add a cover photo
            </h5>
            <div>
                <input
                    v-if="!showUploadedCover"
                    type="file"
                    name="file"
                    @change="onChangeFile('schoolCover', $event)"
                >

                <div
                    v-else
                    class="flex flex-col w-full"
                >
                    <div class="overflow-hidden w-full">
                        <img
                            :src="`${imageURL}/${newSchoolData.coverImageUrl}`"
                            alt=""
                        >
                    </div>

                    <button
                        class="h-fit ml-auto px-7 py-3 hover:underline"
                        @click="handleChangeImage('schoolCover')"
                    >
                        Change
                    </button>
                </div>
            </div>
        </template>

        <!-- Step 4: Upload an avatar only works for  -->
        <template #step4>
            <h5 class="font-bold text-[18px]">
                Upload an avatar
            </h5>
            <div>
                <input
                    v-if="!showUploadedAvatar"
                    type="file"
                    name="file"
                    @change="onChangeFile('userAvatar', $event)"
                >

                <div
                    v-else
                    class="flex flex-col w-full"
                >
                    <div class="h-[150px] rounded-full w-[150px]">
                        <img
                            :src="`${imageURL}/${newUserData.avatarUrl}`"
                            alt=""
                        >
                    </div>

                    <button
                        class="h-fit mb-auto ml-auto px-7 py-3 hover:underline"
                        @click="handleChangeImage('userAvatar')"
                    >
                        Change
                    </button>
                </div>
            </div>
        </template>

        <template #formFooter>
            <button
                class="!bg-[#002856] ml-auto mt-auto px-4 py-2 text-white w-fit"
                :type="stepIndex === 3 || stepIndex === 2 && isPrincipal ? 'submit': ''"
            >
                {{ formStepData.infoButtonText }}
            </button>
        </template>
    </ConfirmInfo>
</template>
