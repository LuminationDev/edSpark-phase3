import axios from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

export const schoolContactService = {
    fetchSchoolContact: (schoolId: number): Promise<{ location: string, website: string, email: string, phone: string, fax: string } | any> => {
        const contactRequestBody = {
            school_id: schoolId
        }
        return axios.post(API_ENDPOINTS.SCHOOL.FETCH_SCHOOL_CONTACT, contactRequestBody).then(res => {
            if (res.data.result) {
                const parsedData = typeof res.data.school_contact == 'string' ? JSON.parse(res.data.school_contact) : res.data.school_contact
                const result = {}
                result['location'] = parsedData.location || ""
                result['website'] = parsedData.website || ""
                result['email'] = parsedData.email || ""
                result['phone'] = parsedData.phone || ""
                result['fax'] = parsedData.fax || ""
                return result

            } else {
                return {}
            }
        })
    }
}

