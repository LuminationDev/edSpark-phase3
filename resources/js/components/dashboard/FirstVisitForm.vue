<script setup>
    /**
     * Important imports
     */
    import { reactive, ref, onMounted } from 'vue';

    /**
     * Delightfully robust importation of items and components
     */
    import ConfirmInfo from './ConfirmInfo.vue';
    import SearchDropdown from 'search-dropdown-vue';

    const props = defineProps({
        isFirstVisit: {
            type: Boolean,
            required: true
        },
        claims: {
            type: Object,
            required: true
        }
    });

    const steps = ref([
        {'step_no':1,'step_valid':false,'step_skip':true},
        {'step_no':2,'step_valid':false,'step_skip':true},
        {'step_no':3,'step_valid':false,'step_skip':true},
        {'step_no':4,'step_valid':false,'step_skip':true},
    ]);

    const state = reactive({
        infoButtonText: 'Next',
        infoWelcomeHeaderText: 'Welcome to edSpark!',
        infoWelcomeHeaderBlurb: 'We see this is your first time visiting us, please confirm the following information.',
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
        // name: '',
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
        customSiteSearch: []
    });

    const name = ref('');

    onMounted(() => {
        props.claims.forEach(claim => {
            switch (claim.claim) {
                case 'name':
                        name.value = claim.value;
                    break;

                case 'email':
                        state.email = claim.value;
                    break;

                case 'mainsiteid':
                        state.siteId = claim.value;
                    break;

                case 'mainrolecode':
                        state.roleId = claim.value;
                    break;

                default:
                    break;
            }
        });
    });

    const emits = defineEmits(['handleEmitSubmit']);

    const handleSubmitFormClick = () => {
        const confirmedData = {
            name: state.name,
            email: state.email,
            role: state.role,
            site: state.site,
            yearLevels: state.yearLevels,
            subjects: state.subjects,
            interests: state.interests,
            biography: state.biography,
            avatar: state.avatar
        }
        emits('handleEmitSubmit', confirmedData)
    }

</script>

<template>
    <ConfirmInfo
        ref="multiStepForm"
        :steps="steps"
        @onComplete="handleSubmitFormClick"
        @validateStep="validateStep"
        @handleStep="handleStep"
        @closePopup="closePopup"
    >
        <template #formHeader>
            <h3 class="text-[36px] text-black font-bold">
                {{ state.infoWelcomeHeaderText }}
            </h3>
            <p class="text-[18px] font-medium">
                {{ state.infoWelcomeHeaderBlurb }}
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
                    v-model="state.email"
                    type="email"
                    name="Email"
                    placeholder="Email..."
                >
            </div>

            <div class="flex flex-col">
                <label for="Role">Your Role</label>
                <SearchDropdown
                    class="searchable_dropdown"
                    :options="state.allRoles"
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
                    :options="state.allSites"
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
                    v-for="(year,index) in state.years"
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
                    v-for="(subject,index) in state.allSubjects"
                    :key="index"
                    class="flex gap-2"
                >
                    <input
                        :id="subject"
                        v-model="state.subjects"
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
                    v-for="(tech,index) in state.digitalTechnologies"
                    :key="index"
                    class="flex gap-2"
                >
                    <input
                        :id="tech"
                        v-model="state.interests"
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
                    v-model="state.biography"
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
                    :src="state.avatarURL"
                    alt=""
                >
            </div>
        </template>

        <template #formFooter>
            <button
                class="!bg-[#002856] ml-auto mt-auto px-4 py-2 text-white w-fit"
                :type="stepIndex === 3 ? 'submit': ''"
            >
                {{ state.infoButtonText }}
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
</template>
