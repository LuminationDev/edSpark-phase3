import {serverURL} from "@/js/constants/serverUrl";

export const API_ENDPOINTS = {
    SCHOOL: {
        FETCH_ALL_SITES: `${serverURL}/fetchAllSites`,
        FETCH_STAFF_FROM_SITE: `${serverURL}/fetchStaffFromSite/`,
        GET_NOMINATED_USER_FROM_SCHOOL: `${serverURL}/getNominatedUsersFromSchool`,
        NOMINATE_USER_FOR_SCHOOL: `${serverURL}/nominateUserForSchool`,
        DELETE_NOMINATED_USER: `${serverURL}/deleteNominatedUser`,
        CREATE_SCHOOL: `${serverURL}/createSchool`,
        UPDATE_SCHOOL: `${serverURL}/updateSchool`,
        CREATE_OR_UPDATE_SCHOOL_CONTACT: `${serverURL}/createOrUpdateSchoolContact`,
        FETCH_SCHOOL_CONTACT: `${serverURL}/fetchSchoolContact`,
        CHECK_IF_USER_CAN_EDIT_SCHOOL: `${serverURL}/checkUserCanEdit`,
        FETCH_ALL_SCHOOLS: `${serverURL}/fetchAllSchools`,
        FETCH_SCHOOL_BY_NAME: `${serverURL}/fetchSchoolByName/`,
        FETCH_FEATURED_SCHOOL: `${serverURL}/fetchFeaturedSchools`
    },
    ADVICE: {
        FETCH_ADVICE_POSTS: `${serverURL}/fetchAdvicePosts`,
        FETCH_ADVICE_POSTS_BY_TYPE_DAG : `${serverURL}/fetchAdvicePostByType/DAG advice`,
        FETCH_ADVICE_POSTS_BY_TYPE_PARTNER : `${serverURL}/fetchAdvicePostByType/Partner`,
        FETCH_ADVICE_POSTS_BY_TYPE_YOUR : `${serverURL}/fetchAdvicePostByType/${['Your Classroom', 'Your Work', 'Your Learning']}`,
    },
    SOFTWARE: {
        FETCH_SOFTWARE_POSTS: `${serverURL}/fetchSoftwarePosts`
    },
    HARDWARE: {
        FETCH_HARDWARE_POSTS: `${serverURL}/fetchAllProducts`,
        FETCH_ALL_BRANDS: `${serverURL}/fetchAllBrands`,
        FETCH_ALL_CATEGORIES: `${serverURL}/fetchAllCategories`

    },
    PARTNER: {
        FETCH_ALL_PARTNERS: `${serverURL}/fetchAllPartners`

    },
    EVENT: {
        ADD_EVENT_RECORDING: `${serverURL}/addEventRecording`,
        CHECK_EVENT_RECORDING: `${serverURL}/checkEventRecording/`,
        CHECK_IF_USER_RSVPED : `${serverURL}/checkIfUserRsvped`,
        ADD_RSVP_TO_EVENT: `${serverURL}/addRsvpToEvent`,
        FETCH_EVENT_POSTS: `${serverURL}/fetchEventPosts`,




    },
    USER: {
        FETCH_ALL_BOOKMARKS_WITH_TITLE: `${serverURL}/fetchAllBookmarksWithTitle`

    },
    IMAGE: {
        UPLOAD_IMAGE: `${serverURL}/uploadImage`,
        IMAGE_UPLOAD_EDITOR_JS: `${serverURL}/imageUploadEditorjs`

    }
}