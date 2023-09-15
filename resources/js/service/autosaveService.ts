import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import axios from 'axios'

export const autosaveService = {
    getExpiryDateMonth: () :string => {
        // Get the current date
        const currentDate = new Date();
        currentDate.setDate(currentDate.getDate() + 30);

        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    },
    savePost : (user_id,type,data)  => {
        console.log(data)
        if(type === 'software'){
            /**
             * from softwareForm, data is
             * authorName
             * content
             * coverImage
             * excerpt
             * extra_content
             * softwaretype_id
             * tags
             * title
             */
            const expiryDate = autosaveService.getExpiryDateMonth()
            console.log(expiryDate)
            const requestPayload = {
                user_id: user_id,
                post_id: 10,
                post_type: type,
                content: JSON.stringify(data),
                exp_date: expiryDate

            }

            return axios.post(API_ENDPOINTS.AUTOSAVE.AUTOSAVE,requestPayload)
        }

    },
    getAutosave: (user_id, type) => {
        // Define the URL endpoint to get autosave data
        const url = API_ENDPOINTS.AUTOSAVE.AUTOSAVE;

        // Make the GET request
        return axios.get(url, {
            params: {
                user_id: user_id,
                post_type: type
            }
        }).then(response => {
            if (response.data && response.data.content) {
                // If the content exists, parse it and return
                return JSON.parse(response.data.content);
            } else {
                throw new Error("No autosave found.");
            }
        }).catch(error => {
            // Handle the error gracefully. You can update this to more specific errors if needed.
            console.error(error.message);
            return null;
        });
    }
}