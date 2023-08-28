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
        FETCH_ADVICE_POSTS_BY_TYPE_DAG: `${serverURL}/fetchAdvicePostByType/DAG advice`,
        FETCH_ADVICE_POSTS_BY_TYPE_PARTNER: `${serverURL}/fetchAdvicePostByType/Partner`,
        FETCH_ADVICE_POSTS_BY_TYPE_YOUR: `${serverURL}/fetchAdvicePostByType/${['Your Classroom', 'Your Work', 'Your Learning']}`,
        FETCH_ADVICE_POST_BY_ID: `${serverURL}/fetchAdvicePostById/`,
        FETCH_RELATED_ADVICE: `${serverURL}/fetchRelatedAdvice`

    },
    SOFTWARE: {
        FETCH_SOFTWARE_POSTS: `${serverURL}/fetchSoftwarePosts`,
        FETCH_SOFTWARE_POST_BY_ID: `${serverURL}/fetchSoftwarePostById/`,
        FETCH_RELATED_SOFTWARE: `${serverURL}/fetchRelatedSoftware`

    },
    HARDWARE: {
        FETCH_HARDWARE_POSTS: `${serverURL}/fetchAllProducts`,
        FETCH_ALL_BRANDS: `${serverURL}/fetchAllBrands`,
        FETCH_ALL_CATEGORIES: `${serverURL}/fetchAllCategories`,
        FETCH_HARDWARE_BY_ID: `${serverURL}/fetchProductById/`,
        FETCH_HARDWARE_BY_BRAND: `${serverURL}/fetchProductByBrand/`,
        FETCH_RELATED_HARDWARE : `${serverURL}/fetchRelatedProduct`
    },
    PARTNER: {
        FETCH_ALL_PARTNERS: `${serverURL}/fetchAllPartners`,
        FETCH_PARTNER_BY_ID: `${serverURL}/fetchPartnerById/`,
    },
    EVENT: {
        ADD_EVENT_RECORDING: `${serverURL}/addEventRecording`,
        CHECK_EVENT_RECORDING: `${serverURL}/checkEventRecording/`,
        CHECK_IF_USER_RSVPED: `${serverURL}/checkIfUserRsvped`,
        ADD_RSVP_TO_EVENT: `${serverURL}/addRsvpToEvent`,
        FETCH_EVENT_POSTS: `${serverURL}/fetchEventPosts`,
        FETCH_EVENT_POST_BY_ID: `${serverURL}/fetchEventPostById/`,
    },
    USER: {
        FETCH_USER_BY_ID: `${serverURL}/fetchUser/`,
        FETCH_USER_BY_EMAIL: `${serverURL}/fetchUserByEmail/`,
        CREATE_USER: `${serverURL}/createUser`,
        UPDATE_FIRST_TIME_VISIT_USER: '/updateFirstTimeVisitUser',
        UPDATE_USER: '/updateUser',
        CHECK_EMAIL: `${serverURL}/checkEmail`,
        GET_USER_METADATA: `${serverURL}/getUserMetadata`,


    },
    LIKE: {
        LIKE: `${serverURL}/like`,
        FETCH_ALL_LIKES: `${serverURL}/fetchAllLikes`,
        FETCH_ALL_LIKES_BY_TYPE: `${serverURL}/fetchAllLikesByType`,
    },
    BOOKMARK:{
        BOOKMARK: `${serverURL}/bookmark`,
        FETCH_ALL_BOOKMARKS: `${serverURL}/fetchAllBookmarks`,
        FETCH_ALL_BOOKMARKS_WITH_TITLE: `${serverURL}/fetchAllBookmarksWithTitle`,
        FETCH_ALL_BOOKMARKS_BY_TYPE: `${serverURL}/fetchAllBookmarksByType`,

    },
    IMAGE: {
        UPLOAD_IMAGE: `${serverURL}/uploadImage`,
        IMAGE_UPLOAD_EDITOR_JS: `${serverURL}/imageUploadEditorjs`

    },
    RSVP: {
        FETCH_RSVP_BY_EVENT_ID: '/fetchRsvpByEventId/',
        ADD_RSVP_TO_EVENT: '/addRsvpToEvent',
        REMOVE_RSVP_FROM_EVENT: '/removeRsvpFromEvent',
        CHECK_IF_USER_RSVPED: '/checkIfUserRsvped',
        ADD_EVENT_RECORDING: '/addEventRecording',
        CHECK_EVENT_RECORDING: '/checkEventRecording/',
    }
}