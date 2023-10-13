import axios, {AxiosResponse} from "axios";
import _ from "lodash";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {parseToJsonIfString} from "@/js/helpers/jsonHelpers";
import {schoolDataFormDataBuilder} from "@/js/helpers/schoolDataHelpers";
import {SchoolDataType} from "@/js/types/SchoolTypes";

type CheckCanEditResponseType = {
    status: string;
    result: string;
    canNominate: boolean
}
export const schoolService = {
    fetchSchoolByName: async (schoolName: string): Promise<any> => {
        return axios.get(`${API_ENDPOINTS.SCHOOL.FETCH_SCHOOL_BY_NAME}${schoolName}`).then(res => {
            const {data} = res;
            const {content_blocks, tech_used, cover_image, logo} = parseToJsonIfString(data);
            return ({
                ...data,
                content_blocks: content_blocks ? parseToJsonIfString(content_blocks) : {},
                tech_used: tech_used ? parseToJsonIfString(tech_used) : [],
                cover_image: cover_image ? cover_image.replace("/\\/g", "") : '',
                logo: logo ? logo.replace("/\\/g", "") : ''
            })
        })
    },
    checkIfUserCanEdit: async (site_id, user_id, school_id): Promise<AxiosResponse<CheckCanEditResponseType>> => {
        const data = {
            "site_id": site_id,
            "user_id": user_id,
            "school_id": school_id
        }
        return axios.post(
            API_ENDPOINTS.SCHOOL.CHECK_IF_USER_CAN_EDIT_SCHOOL,
            data
        )
    },
    fetchPendingSchoolByName: async (schoolName, site_id, user_id, school_id): Promise<SchoolDataType | any> => {
        const data = {
            "site_id": site_id,
            "user_id": user_id,
            "school_id": school_id
        }
        return axios.post(
            API_ENDPOINTS.SCHOOL.FETCH_PENDING_SCHOOL_BY_NAME + schoolName,
            data
        ).then(res => {
            if (res.data.result) {
                const result = res.data.result
                console.log('result issssss')
                console.log(result)
                const {content_blocks, tech_used, cover_image, logo} = parseToJsonIfString(result);
                return ({
                    ...result,
                    content_blocks: content_blocks ? parseToJsonIfString(content_blocks) : {},
                    tech_used: tech_used ? parseToJsonIfString(tech_used) : [],
                    cover_image: cover_image ? cover_image.replace("/\\/g", "") : '',
                    logo: logo ? logo.replace("/\\/g", "") : ''
                })
            } else {
                return null
            }
        })
    },
    /**
     * Get school info based on user's site id
     * if user is principal or has `SCHLDR` role, it will also automatically create schoolProfile from the sites
     * @param user_id
     * @param site_id
     */
    getUserSchoolByUserSiteId: async (user_id: number, site_id: number): Promise<AxiosResponse<any>> => {
        const payload = {
            site_id: site_id,
            user_id: user_id
        }
        return axios.post(`${API_ENDPOINTS.SCHOOL.FETCH_USER_SCHOOL}`, payload)
    },
    updateSchool: async (schoolContent, contentBlocks, techUsed, logoStorage, coverImageStorage, colorTheme): Promise<SchoolDataType | null> => {
        const schoolData: SchoolDataType = _.cloneDeep(schoolContent)
        schoolData.content_blocks = contentBlocks
        schoolData.tech_used = techUsed
        const newUpdatedSchoolFormData = schoolDataFormDataBuilder(schoolData)
        newUpdatedSchoolFormData.append('logo', logoStorage ? logoStorage : schoolData.logo)
        newUpdatedSchoolFormData.append('cover_image', coverImageStorage ? coverImageStorage : schoolData.cover_image)

        const schoolMetadata = {school_color_theme: colorTheme};
        newUpdatedSchoolFormData.append('metadata', JSON.stringify(schoolMetadata));

        try {
            const response = await axios({
                url: API_ENDPOINTS.SCHOOL.UPDATE_SCHOOL,
                method: 'post',
                data: newUpdatedSchoolFormData,
                headers: {"Content-Type": "multipart/form-data"}
            });

            if (response.data.data.status === 'Published') {
                const updatedSchoolContent = response.data.data;
                updatedSchoolContent.content_blocks = parseToJsonIfString(updatedSchoolContent.content_blocks);
                updatedSchoolContent.tech_used = parseToJsonIfString(updatedSchoolContent.tech_used);
                return updatedSchoolContent;
            }

            return null;
        } catch (err) {
            console.error('Error while attempting to update school:', err);
            throw err;
        }
    },
    getAllStaffBySiteId: async (siteId: number, currentUserId: number) : Promise<Array<any>> => {
        try {
            const res = await axios.get(`${API_ENDPOINTS.SCHOOL.FETCH_STAFF_FROM_SITE}${siteId}`);
            console.log(res.data);
            return res.data.data.filter(staff => staff.id !== currentUserId).map(staff => {
                return {...staff}
            });
        } catch (error) {
            console.error("Error fetching staff:", error);
            return [];
        }
    },
    getSchoolDelegates: async (siteId: number, schoolId: number, currentUserId: number): Promise<Array<any>> => {
        try {
            const response = await axios({
                method: "POST",
                url: API_ENDPOINTS.SCHOOL.GET_NOMINATED_USER_FROM_SCHOOL,
                data: {
                    "site_id": siteId,
                    "school_id": schoolId,
                    "user_id": currentUserId
                }
            });
            if (response.data.status === 200) {
                return response.data.data
            }
            return []
        } catch (error) {
            console.error("Error fetching nominated users:", error);
            return [];
        }
    },
    nominateNewDelegates: (siteId: number, schoolId: number, nominatedUserId: number) =>{
        return axios({
            method: "POST",
            url: API_ENDPOINTS.SCHOOL.NOMINATE_USER_FOR_SCHOOL,
            data: {
                "site_id": siteId,
                "school_id": schoolId,
                'nominated_user_id': nominatedUserId
            }
        }).then(res =>{
            return res.data
        })
    },
    removeDelegates:(siteId: number, schoolId: number, nominatedUserId: number) : Promise<any> => {
        return axios({
            method: "POST",
            url: API_ENDPOINTS.SCHOOL.DELETE_NOMINATED_USER,
            data: {
                "site_id": siteId,
                "school_id": schoolId,
                'nominated_id_delete': nominatedUserId
            }
        }).then(res =>{
            return res.data
        })
    }


}